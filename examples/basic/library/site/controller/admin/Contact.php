<?php
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */



class Contact extends Content{
	private $my_action 		= "admin/contact/commit";
	private $my_redirect 	= "admin/contact/edit";
	/**
	 * @param $arrayRest
	 * @return void
	 */
	public function __construct($arrayRest = NULL){
		parent::__construct($arrayRest);
		$this->category_id = 22;
	}

	public function init(){
		$returnResult = parent::init();
		
		//$ContentVO->toStdClass(Config::getLocale());
		$SelectData = new ContentSelectView($this->arrayContentsVO);
		$SelectData->setGoToLocation(array("value"=>"admin/contact"));
		
		$SelectData->gerate();
		
		
		$SelectData->setMassiveAttr('visible', FALSE, array(
																'Title',
																'Hat',
																'Description',
																'Content',
																'Author',
																'TemplateUrl',
																'Slug',
																'KeyWords',
																'Date',
																'DateIn',
																'DateOut',
																'Order'
																));
		
		$SelectData = $SelectData->getFormData();
		
		$HttpContentResult = $returnResult->getHttpContentResult();
		
		$HttpContentResult->selectData = $SelectData;
		
		$returnResult->setHttpContentResult($HttpContentResult);
		$HttpResult = $returnResult;
		//para o botao de inserir
		$HttpContentResult->url_insert = Config::getRootPath("admin/contact/edit/");
		include (Config::getFolderView('admin/content/select.php'));exit();
		//print_r($returnResult);exit();
		return $returnResult;
	}	

	/**
	 * @return para poder inserir
	 */
	public function insert(){
//		echo "cocococo";exit();
		return $this->createFormData();
	}
	
	public function edit($ReturnResultVO = NULL){
		return $this->createFormData($ReturnResultVO);
	}	

	public function delete(){
		parent::delete();
		Navigation::redirect("admin/contact");
		exit();
	}		
	private function createFormData($ReturnResultVO = NULL){
		//busca o primeiro content vinculada com essa categoria
		if($this->category_id > 0){
			$CategoryVO = new CategoryVO();
			$CategoryVO->setId($this->category_id, TRUE);
			$array_content = $CategoryVO->getLinks("content", 1);
			//se tiver mais de um link
			if(count($array_content) > 0){
				//pega o primeiro pois aqui só importa 1.
				$LinkVO = $array_content[0];
				if(FALSE){
					//ajuda aptana
					$LinkVO = new LinkVO();
				}
				//echo Debug::li("tem content vinculada com id:".$LinkVO->getLinkedTableId());
				//inicia a ContentForumVO
				$this->ContentSiteVO->setId($LinkVO->getLinkedTableId(), TRUE);
			}
		}else if($this->content_id > 0){
			$this->my_action .= "/id.$this->content_id/";
		}
		
		$formData = new ContentFormView($this->ContentSiteVO, Config::getRootPath($this->my_action));
		
		
		$ImageFormView = new ImageFormView();
		$ImageFormView->setFormLabel("Selecionar Foto");
		$ImageFormView->setQuantity(0);

//		$ImageFormView->setDescription(array('label'=>Translation::text('Link'), 'visible'=>false, 'type'=>'simpleText'));
		
		$FileFormView = new FileFormView();
		$FileFormView->setFormLabel("Selecionar Arquivo");
		$FileFormView->setQuantity(0);
		
//		$formData->setPersonalInput(array("name"=>"destaque[]","label"=>"Mostrar em qual Menu?", "options"=>array((object) array("name"=>"Menu Topo", "id"=>"2"), (object) array("name"=>"Menu Rodapé", "id"=>"3"))));

		//trocando o rótulo para Content
		$formData->setDescription(array("label"=>"Edite a coluna da Esquerda:",'type'=>'htmlText'));
		$formData->setContent(array("label"=>"Edite a coluna da Direita:"));
		$formData->setCategory(array("visible"=>FALSE,"name"=>"category[]","selected"=>array($this->category_id)));
		
		$formData->setImage($ImageFormView);
		$formData->setFile($FileFormView);
		
		$formData->setMassiveAttr('visible', FALSE, array(
														'Name',
														'Title',
														'Hat',
//														'Description',
														//'Content',
														'Author',
														'TemplateUrl',
														'Slug',
														'KeyWords',
														'Date',
														'DateIn',
														'DateOut',
														'Order'
														));
		
		$formData->setActive(array("value"=>"1", "visible"=>FALSE));	
		$this->my_redirect = "admin/contact/edit/";

		parent::edit($formData, TRUE, NULL, $this->my_redirect, NULL,  $ReturnResultVO);	

	}	


	public function commit(){
		//		echo "cococ";exit();
		$this->redirect_with_id = TRUE;
		if(DataHandler::getValueByArrayIndex($_POST, "destaque")){
			if(is_array($_POST["category"])){
				$_POST["category"] = DataHandler::appendArray($_POST["category"], $_POST["destaque"]);
			} else {
				throw new Exception("envie a array de categoria. Sem isso a content nao funciona.");
			}
		}
		parent::commit();
	}
	
	
}