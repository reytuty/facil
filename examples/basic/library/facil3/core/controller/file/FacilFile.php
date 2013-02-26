<?php
include_once "library/facil3/core/controller/interface/HTTPControllerInterface.class.php" ;
include_once "library/facil3/utils/Navigation.class.php";
include_once "library/facil3/core/modules/file/dao/FileDAO.class.php";
include_once "library/facil3/core/controller/file/FileInfoPostVO.php";

//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";
/**
 * @author 		: MAuricio Amorim
 * @date		: 05/12/2010
 * @desc		: Controller de arquivos e relação com link
 * 					Essa controller não controla permissão de usuário, portanto se precisar controlar permissões, 
 * 					faça uma controller que extenda a essa
 *
 * @autor		: Renato Miawaki
 * @date		: 28/07/2011
 * @desc		: Para utilizar essa controller sem extende-la, ou seja, em outra controller faça o seguinte
 * 					1) não passe nada na contrutora
 * 					2) utilize o FileInfoPostHandler para tratar o envio de arquivos e organização
 * 					3) instancie essa classe e de o loop fazendo
 * 						$FacilFile->resetInfoPost($InfoPostVO);
 * 						$FacilFile->insert();
 * 
 * 				O insert vai retornar um objeto ReturnResultVO e este contem o resultado da tentativa de insersão
 */
class FacilFile implements HTTPControllerInterface{
	public $infoPost;

	/**
	 * para resetar pode mudar pois o atributo é público
	 * @var string path folder for upload file
	 * 
	 */
	public $defaultFolderForNewFiles;
	public $defaultFile404;
	public $defaultMaxSize;
	
	public $moduleName;
	protected $arrayVariable;
	protected $arrayRestFolder;
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
            //por padrão ele popula as infos que ele possui com o que veio na restFolder, pode ser resetado utilizando resetInfoPost
            $this->infoPost = new FileInfoPostVO($this->arrayVariable);
        }
        $this->defaultFolderForNewFiles 	= Config::getFolderView()."/upload/file/";
        $this->moduleName					= "facil_file";
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
     * passe um novo FileInfoPostVO caso não queira usar o que está na rest folder
     * @param FileInfoPostVO $FileInfoPostVO
     * @return void
     */
    public function resetInfoPost(FileInfoPostVO $FileInfoPostVO = NULL){
    	$this->infoPost = $FileInfoPostVO;
    }
 
    public function getFile(){
    	//pega o id da imagem enviada na url - esse é a preferencia
    	$file_id 	= DataHandler::forceInt($this->infoPost->request_file_id);
//    	print_r($file_id);exit();
    	$url 		= "";
//    	echo Debug::li("1");
    	
    	if(!$file_id > 0){
//    		echo Debug::li("2");
    		//só considera a url se não tem id
    		$url 		= $this->infoPost->request_file_url;
    	}
    	$urlFile = $url;
//    	echo $url;
//    	echo Debug::li("3");
		if($urlFile == ""){
//			echo Debug::li("4");
			$FileVO = new FileVO();
			$ReturnResultVO = $FileVO->setId($file_id, TRUE);
//			print_r($FileVO);
			if($ReturnResultVO->success){
				
//				echo Debug::li("5  : ".$FileVO->getURL());
				$urlFile = DataHandler::removeDobleBars($FileVO->getURL());
			}
		} else {
//			echo Debug::li("6");
//			echo $urlFile."cacacaac";
			$urlFile = DataHandler::removeDobleBars(str_replace(array("..", ""), "", $urlFile));
		}
//		echo $urlFile;
//		exit();
//		echo Debug::li("7");
		
		if($urlFile == "" || !file_exists($urlFile)){
//			echo Debug::li("8 : ".$urlFile."  nao existe, entao:".$this->defaultFile404);
//			exit();
			//não encontrou a filem, seta a url com a url da filem padrão
			$urlFile = $this->defaultFile404;
		}
//		echo $urlFile;
//		exit();
		Navigation::redirect($urlFile);
		exit();
    }
    
    
  
     /*
     * recebe a filem por post
     * @return ReturnResultVO
     */
    public function insert(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	
    	//pega os dados baseado na infoPost
	    $VO = new FileVO();
		if($this->infoPost->file_info_id){
			$VO->setId($this->infoPost->file_info_id, TRUE);
		}
		$VO->setActive($this->infoPost->file_info_active);
		$VO->setName($this->infoPost->file_info_name);
		$VO->setDescription($this->infoPost->file_info_description);
		$VO->setType($this->infoPost->file_info_type);
		$VO->setAuthor($this->infoPost->file_info_author);
		$VO->setLocale($this->infoPost->file_info_locale);
		$VO->setOrder($this->infoPost->file_info_order);
		
		//("JÁ") gera id para criar pasta onde vai ser guardado o arquivo
		$ReturnResultFileVO = $VO->commit();
		if($ReturnResultFileVO->success){
			$ReturnResultFileVO->result = $VO->getId();
		} else {
			//erro, os motivos estão na ReturnResultVO abaixo
			return $ReturnResultFileVO;
		}
		//pega o id da file
		$FILE_ID = $VO->getId();
		
		$ReturnResultFileVO = new ReturnResultVO();
		
		if(isset($this->infoPost->file_data) && !$this->infoPost->file_info_id > 0){
			set_time_limit(0);
			
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
				case "text/plain":
					$extension = "txt";
					break;
				default:
					$extension = strtolower($sentExtension);
					break;
			}
			//verifica se a pasta existe, se não existir, inventa
			DataHandler::createFolderIfNotExist($this->defaultFolderForNewFiles);
			// pasta de upload de files está no config.php
			$tempFolder = DataHandler::removeDobleBars($this->defaultFolderForNewFiles."/".$FILE_ID); 
			DataHandler::createFolderIfNotExist($tempFolder);
			
			$tempUrl = $tempFolder."/".strtolower($name.".".$extension);
			$i=2;
			while(file_exists($tempUrl)){
				$tempUrl 	= $tempFolder."/".strtolower($name."-".$i.".".$extension);
				$i++;
			}
			$VO->setUrl($tempUrl);
			$ReturnResultFileVO = $VO->commit();
			//Debug::li("aaa");
			
			if($ReturnResultFileVO->success){
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
					$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::WARNING_FILE_NO_LINKED_TABLE"));
				}
				//movendo o arquivo para sua respectiva pasta.
				$localFile = $VO->getUrl();
				if(!move_uploaded_file($sentFileData['tmp_name'], $localFile)){
					$ReturnResultVO->success = false;
					$ReturnResultVO->addMessage(Translation::text("Arquivo não encontrado"));
					return $ReturnResultVO;
				} else {
					$ReturnResultVO->success 	= TRUE;
					$ReturnResultVO->result 	= $VO->getId();
					$ReturnResultVO->addMessage(Translation::text("Arquivo gravado"));
				}
			} else {
				return $ReturnResultFileVO;
			}
			return $ReturnResultVO;
		} else {
			$ReturnResultFileVO = new ReturnResultVO();
			$ReturnResultFileVO->addMessage("Envie o Filedata"); // nao veio filedata
			return $ReturnResultFileVO;
		}
    }
}
