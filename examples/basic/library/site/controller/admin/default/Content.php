<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";
include_once "library/facil3/core/modules/content/ContentFormView.class.php";
include_once "library/facil3/core/modules/content/ContentSelectView.class.php";
//include_once Config::FOLDER_APPLICATION."controller/admin/default/GenericAdminController.class.php";
include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";

include_once(Config::FOLDER_APPLICATION."controller/Admin.php");
/**
 * @author 	: Renato Miawaki
 * @desc	: 	Essa é a classe de manipular content no admin, apenas admins logados podem utilizar seus metodos
 * 				Não está utilizando FacilContent
 */
class Content extends Admin{
	protected $arrayVariable;
	protected $arrayRestFolder;
	
	protected $my_redirect 		= "admin/content/select";
	protected $my_edit_page 	= 'admin/content/edit.php';
	protected $my_edit_method 	= "edit";
	
	protected $ContentSiteDAO;
	/**
	 * @desc id passado na url
	 */
	protected $content_id;
	
	/**
	 * @var mudar para true para quando, no commit, quiser que ele adicione o id no final da url
	 */
	protected $redirect_with_id = FALSE;
	/**
	 * @desc id da categoria passado pela construct da controller que extende essa
	 */
	protected $category_id;
	
	
	protected $arrayContentsVO = array();
	
	/**
	 * @desc redirect a ser passado na url 
	 */
	protected $redirect;
	protected $ContentSiteVO;
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
		parent::__construct($arrayRestFolder);
		//define a dao a ser usada em toda a controler
		$this->ContentSiteDAO = ContentSiteDAO::getInstance();
		//define a vo a ser usada em toda a controler
		$this->ContentSiteVO = $this->ContentSiteDAO->getVO();
		//pega id passado na url
		$this->content_id = DataHandler::getValueByArrayIndex($this->arrayVariable, 'id');

		//popula a a contentvo com os dados conforme o id passado
		if($this->content_id){
			$this->ContentSiteVO->setId($this->content_id , TRUE);
		}
	
	}
	
	/**
	 * 
	 * @return HttpResult
	 */
	public function init($order_by = "order", $order_type = " ASC "){
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		//se foi passado o id da categoria entao vai buscar todos os contentents vinculados a mesma
		//echo Debug::li($this->category_id);exit();
		if($this->category_id > 0){
			$LinkDAO = LinkDAO::getInstance();
			$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $this->category_id, "content", NULL, 1,NULL, NULL,$order_by, $order_type);
//			echo $this->category_id;
			//Debug::print_r($returnDataVO);exit();
			//verifica se o resultado é uma categoryVO
			if($returnDataVO->success && count($returnDataVO->result)>0){
				foreach($returnDataVO->result as $LinkVO){
					//Debug::print_r($LinkVO);
					$tempReturnDataVO = $LinkVO->getLinkedVO();
					//Debug::print_r($tempReturnDataVO);exit();
					if($tempReturnDataVO->success){
						if($tempReturnDataVO->result->active >= 0){
							$this->arrayContentsVO[] = $tempReturnDataVO->result;
						}//end if active
					}
				}
				//exit();
			}
		}
		$retornoDaPaginaHTML->vo = $this->ContentSiteVO;
		$retornoDaPaginaHTML->arrayContentsVO = $this->arrayContentsVO;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);exit();
		return $returnResult;		
	}

	public function detail(){
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$retornoDaPaginaHTML->vo 	= $this->ContentSiteVO;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	/**
	 * @param $_ContentFormView		ContentFormView
	 * @param $direct_show_view		Boolean default TRUE
	 * @param $form_action			String do action no form
	 * @param $redirect_to			String do redirect to depois do commit
	 * @param $arrayFields			array de fields
	 * @param $p_ReturnResultVO		opicional parametro para retorno de mensagem de erro ou sucesso
	 * @return void or HttpResult
	 */
	public function edit($_ContentFormView = NULL, $direct_show_view = TRUE, $form_action = NULL, $redirect_to = NULL, $arrayFields = NULL, $p_ReturnResultVO = NULL){
		if($_ContentFormView){
			$formData = $_ContentFormView;
		} else {
			if(FALSE){
				$this->ContentSiteVO = new $ContentSiteVO();
			}
			//se for enviado o content_id por parametro, usa ele como id
			if($this->content_id > 0){
				$this->ContentSiteVO->setId($this->content_id, TRUE);
			}
			$action_path = Config::getRootPath('admin/content/commit');
			//se for enviado o $form_action, então utiliza o que foi enviado
			if($form_action){
				$action_path = Config::getRootPath($form_action);
			} 
			$formData = new ContentFormView($this->ContentSiteVO, $action_path);
			
		} 
		//$formData->setModules();
		
		
		$HttpResult = new HttpResult();
		$HttpResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		
		$retornoDaPaginaHTML->arrayVariable->form_data = $formData->getFormData();
		if($redirect_to != NULL){
			$retornoDaPaginaHTML->arrayVariable->form_data->fields->redirect_to = (object) array(
																						"name"=>"redirect_to",
																						"value"=>$redirect_to,
																						"visible"=>false
																						);
		}
		if($arrayFields != NULL){
			//passou array de fields para adicionar
			//adiciona
			foreach($arrayFields as $field_item){
				$retornoDaPaginaHTML->arrayVariable->form_data->fields[] = $field_item;
			}
		}
		//$redirect_to = NULL
		//buscando imagens vinculadas
		$array_image_vo = $this->ContentSiteVO->getImages();
		$array_image_std = array();
		foreach($array_image_vo as $VO){
			$StdVO = $VO->toStdClass();
			$array_image_std[] = $StdVO;
		}
		$retornoDaPaginaHTML->arrayVariable->array_image 	= $array_image_std;
		//se for enviado o ReturnResultVO no parametro, adiciona como atributo
		if($p_ReturnResultVO){
//			print_r($p_ReturnResultVO);exit();
			$retornoDaPaginaHTML->return_result_vo = $p_ReturnResultVO;
		}
		//buscando arquivos vinculadas
		$array_file_vo = $this->ContentSiteVO->getFiles();
		$array_file_std = array();
		foreach($array_file_vo as $VO){
			$StdVO = $VO->toStdClass();
			$array_file_std[] = $StdVO;
		}
		$retornoDaPaginaHTML->arrayVariable->array_file 	= $array_file_std;
		
		//salvando o objeto de resultado de html no retorno
		$HttpResult->setHttpContentResult($retornoDaPaginaHTML);
		
		//se for passado como true vai usar o layout abaixo.
		if($direct_show_view){
			include (Config::getFolderView($this->my_edit_page));exit();
		}
		return $HttpResult;
	}

	/**
	 * @param $field_name string
	 * @param $field_value *
	 * @return ReturnResultVO
	 */
	protected function validateContentFields($field_name, $VO){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		//para o aptana
		if(FALSE){
			$VO = new ContentSiteVO();
		}
		switch($field_name){
			case "name":
				$ReturnResultVO->success = (Validation::blank($VO->getName(), 1));
				//echo Debug::li("  getName:".$VO->getName());
				break;
			case "title":
				$ReturnResultVO->success = (Validation::blank($VO->getTitle(), 1));
				break;
			case "hat":
				$ReturnResultVO->success =(Validation::blank($VO->getHat(), 1));
				break;
			case "description":
				$ReturnResultVO->success =(Validation::blank($VO->getDescription(), 1));
				break;
			case "content":
				$ReturnResultVO->success =(Validation::blank($VO->getContent(), 1));
				break;
			case "files":
				$returnTemp = $_FILES["file"];
				$ReturnResultVO->success = (count($returnTemp) > 0);
				break;
			case "images":
				$returnTemp = $_FILES["image"];
				$ReturnResultVO->success = (count($returnTemp) > 0);
				break;
		}
		return $ReturnResultVO;
	}
	/**
	 * Da redirect
	 * Envie redirect_with_id para o seu redirect_to ir com o parametro id no final
	 * @return void
	 */
	public function commit($redirect_page = TRUE, $link_table = "image"){
		$contentVO = new ContentSiteVO();
		if(isset($_POST["id"])){
			$contentVO->setId($_POST["id"], TRUE);
		}
		
		$contentVO->setFetchArray($_POST);
		
						//antes de tudo, faz a validação
						$ValidationReturnResultVO = new ReturnResultVO();
						$ValidationReturnResultVO->success = TRUE;
						if(DataHandler::getValueByArrayIndex($_POST, "array_validation")){
							$array_validation = DataHandler::getValueByArrayIndex($_POST, "array_validation");
							//array de fields que deveriam ser validados e estão errados
							$array_fields_errors = array();
							if(is_array($array_validation)){
								//se for array, valida a array
								//varre a validação
								foreach($array_validation as $field_name){
									$temp_ReturnResultVO = $this->validateContentFields($field_name, $contentVO);
									if(!$temp_ReturnResultVO->success){
										//echo Debug::li("Content.php erro na validacao : $field_name ");
										//da o push na array_fields error
										$array_fields_errors[] = $field_name;
										//já muda o success do result para false para saber que não precisa mais commitar
										$ValidationReturnResultVO->success = FALSE;
									}
								}
							} else if(is_string($array_validation)){
								//se for só uma string valida só 1 deles
								$temp_ReturnResultVO = $this->validateContentFields($field_name, $contentVO);
								if(!$temp_ReturnResultVO->success){
									//da o push na array_fields error
									$array_fields_errors[] = $field_name;
									//já muda o success do result para false para saber que não precisa mais commitar
									$ValidationReturnResultVO->success = FALSE;
								}
							}
						}
		if(!$ValidationReturnResultVO->success){
			//coloco na result a array de fields com error
			$ValidationReturnResultVO->result = $array_fields_errors;
			//retorna para o edit de quem extends essa classe
			return $this->$my_edit_method($ValidationReturnResultVO);
			//daqui nao passa
			exit();
		}
		
		//vai criar as key_words do content
		$key_words = "";
		$key_words .= " ".DataHandler::removeAccent($contentVO->getAuthor());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getContent());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getDescription());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getHat());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getName());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getSlug());
		$key_words .= " ".DataHandler::removeAccent($contentVO->getTitle());
		
		$contentVO->setKeyWords($key_words);
		
		$returnResultVO = $contentVO->commit();
		
		$content_id = $contentVO->getId();
		
				//adicionando link com categoria(s) enviada(s)
				//pega a instancia
				$LinkDAO = LinkDAO::getInstance();
				if(FALSE){
					$LinkDAO = new LinkDAO();
				}
				//deleta vinculos com categoria
				$LinkDAO->deleteAllFromLinkedTableByTableAndTableId('content', $content_id, 'category');
				$have_category_to_commit = FALSE;
				if(!is_array($_POST["category"])){
					$_POST["category"] = array($_POST["category"]);
				}
				$arrayCategory = $_POST["category"];
				//Debug::print_r($arrayCategory);exit();
				$order = 10;
				if(!is_array($_POST["order"])){
					$order = $_POST["order"];
				}
				foreach($arrayCategory as $category_id){
					///print_r($category_id);
					//echo Debug::li("categoria id:".$category_id);
					//cada categoria enviada é mais um link com categoria que deve ser adicionado
					//echo $order;exit();
					$LinkDAO->insert('category', $category_id, 'content', $contentVO->getId(), 1, $order);
					// $contentVO->addLink("category", $category_id);
					$have_category_to_commit = TRUE;
				}
				//exit();
				//caso tenha o que adicionar como link em categoria, commita
				if($have_category_to_commit){
					$contentVO->commit();
				}
				//fim da adição do content em categoria
		//-----------------------------==================================== [[[  add imagens ]]] 
		
		$array_image_file = DataHandler::getValueByArrayIndex($_FILES, "image");
		include_once "library/facil3/core/controller/image/FacilImage.php";
		$FacilImage = new FacilImage();
		//echo Debug::li(":link_table   [".$link_table."]");exit();
		$FacilImage->moduleName = $link_table;
		$FacilImage->defaultFolderForNewImages = "upload/image/";
		for($i = 0; $i < count($array_image_file["name"]); $i++){
			//Debug::print_r($array_image_file);
			$file_image =  array();
			$file_image["name"] 	= $array_image_file["name"][$i];
			$file_image["type"] 	= $array_image_file["type"][$i];
			$file_image["tmp_name"] = $array_image_file["tmp_name"][$i];
			$file_image["error"] 	= $array_image_file["error"][$i];
			$file_image["size"] 	= $array_image_file["size"][$i];
			//adicionar cada image utilizando o módulo de imagem
			$array_config_info_post = array();
			$image_id = NULL;
			if(DataHandler::getValueByArrayIndex($_POST, "image_info_id")){
				if(DataHandler::getValueByArrayIndex($_POST["image_info_id"], $i)){
					$image_id = $_POST["image_info_id"][$i];
				}
			}
			$array_config_info_post["image_info_id"] 			= $image_id;
			$array_config_info_post["image_info_name"] 			= (isset($_POST["image_info_name"]))?$_POST["image_info_name"][$i]:"";
			$array_config_info_post["image_info_description"] 	= (isset($_POST["image_info_description"]))?$_POST["image_info_description"][$i]:"";
			$array_config_info_post["image_info_order"] 		= (isset($_POST["image_info_order"]))?$_POST["image_info_order"][$i]:"";
			
			$array_config_info_post["image_info_locale"] 		= NULL;//$_POST["image_info_locale"][$i];
			$array_config_info_post["image_info_author"] 		= (isset($_POST["image_info_author"][$i]))?$_POST["image_info_author"][$i]:"";
			
			$array_config_info_post["table"] 					= "content";
			$array_config_info_post["table_id"] 				= $content_id;
			$array_config_info_post["Filedata"] 				= $file_image;
			
			$ImageInfoPostVO = new ImageInfoPostVO($array_config_info_post);
			//agora inicia o módulo passando esse info post configurado
			
			$FacilImage->resetInfoPost($ImageInfoPostVO);
			$ReturnResultVO = $FacilImage->insert();
			
		}
		
		//-----------------------------==================================== [[[  add file ]]] 
		$array_image_file = DataHandler::getValueByArrayIndex($_FILES, "file");
		include_once "library/facil3/core/controller/file/FacilFile.php";
		$FacilFile = new FacilFile();
		//nome do módulo no sistema
		$FacilFile->moduleName = "file";
		$FacilFile->defaultFolderForNewFiles = "upload/file/";
		for($i = 0; $i < count($array_image_file["name"]); $i++){
			$file_image =  array();
			$file_image["name"] 	= $array_image_file["name"][$i];
			$file_image["type"] 	= $array_image_file["type"][$i];
			$file_image["tmp_name"] = $array_image_file["tmp_name"][$i];
			$file_image["error"] 	= $array_image_file["error"][$i];
			$file_image["size"] 	= $array_image_file["size"][$i];
			
			//adicionar cada image utilizando o módulo de imagem
			$array_config_info_post = array();
			
			$array_config_info_post["file_info_id"] 			= (isset($_POST["file_info_id"]))?$_POST["file_info_id"][$i]:"";//$_POST["file_info_id"][$i];
			$array_config_info_post["file_info_name"] 			= (isset($_POST["file_info_name"]))?$_POST["file_info_name"][$i]:"";//$_POST["file_info_name"][$i];
			$array_config_info_post["file_info_description"] 	= (isset($_POST["file_info_description"]))?$_POST["file_info_description"][$i]:"";//$_POST["file_info_description"][$i];
			$array_config_info_post["file_info_order"] 			= (isset($_POST["file_info_order"]))?$_POST["file_info_order"][$i]:"";//$_POST["file_info_order"][$i];
			
			$array_config_info_post["file_info_locale"] 		= NULL;//$_POST["file_info_locale"][$i];
			$array_config_info_post["file_info_author"] 		= (isset($_POST["file_info_author"]))?$_POST["file_info_author"][$i]:"";//$_POST["file_info_author"][$i];
			$array_config_info_post["table"] 					= "content";
			$array_config_info_post["table_id"] 				= $content_id;
			$array_config_info_post["Filedata"] 				= $file_image;
			
			$FileInfoPostVO = new FileInfoPostVO($array_config_info_post);
			//agora inicia o módulo passando esse info post configurado
			
			$FacilFile->resetInfoPost($FileInfoPostVO);
			$FacilFile->insert();
		}
		
		
		//falta terminar
		//Navigation::redirect("admin/content/");
		
		
		$redirect_to = $this->my_redirect;
		if(DataHandler::getValueByArrayIndex($_POST, "redirect_to")){
			$redirect_to = DataHandler::getValueByArrayIndex($_POST, "redirect_to");
		}
		
		if($this->redirect_with_id){
			$redirect_to .= "/id.".$content_id;
		}
		if($redirect_page){
			Navigation::redirect($redirect_to);
			exit();
		} else {
			return $contentVO;
		}
	}
	
	public function delete(){
		$ReturnResultVO = new ReturnResultVO();
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, 'id') > 0){
			$LinkDAO = new LinkDAO();
			//deleta vinculos com categoria
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableByTableAndTableId('content', $this->content_id, 'category');	
			if($ReturnDataVO->success){
				$ContentDAO = ContentDAO::getInstance();
				$ContentDAO->delete($this->content_id);
				if($ReturnDataVO->success){
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("conteúdo apagado");
				}else{
					$ReturnResultVO->addMessage("erro ao apagar o conteúdo");
				}
			}else{
				$ReturnResultVO->addMessage("erro ao apagar o conteúdo");
			}
		}
//		print_r($ReturnResultVO);exit();
		return $ReturnResultVO;
	}
	/**
	 * deveria mostrar uma mensagem personalizada para cada controller de sucesso de cadastro.
	 * @return unknown_type
	 */
	public function success(){
		
	}
	/**
	 * Envie o novo estado de active 
	 */
	public function updateActive(){
		$active 	= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "active"));
		$id 		= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$ContentVO = new ContentVO();
		$ContentVO->setId($id, TRUE);
		$ContentVO->setActive($active);
		$Result = $ContentVO->commit();
		echo $Result->toJson();
		exit();
	}
}
