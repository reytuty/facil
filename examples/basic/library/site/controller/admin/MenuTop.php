<?php


include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */



class MenuTop extends Content{
	private $my_action 		= "admin/menu_top/commit";
	private $my_redirect 	= "admin/menu_top/select";
	
	
	/**
	 * @param $arrayRest
	 * @return void
	 */
	public function __construct($arrayRest = NULL){
		parent::__construct($arrayRest);
		$this->category_id = 2;
	}

	public function init(){
		$returnResult = parent::init();
		
		//$ContentVO->toStdClass(Config::getLocale());
		$SelectData = new ContentSelectView($this->arrayContentsVO);
		$SelectData->setGoToLocation(array("value"=>"admin/menu_top"));
		
		$SelectData->gerate();
		
		
		$SelectData->setMassiveAttr('visible', FALSE, array(
//																'Title',
																'Name',
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
		$HttpContentResult->url_insert = Config::getRootPath("admin/menu_top/insert/");
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
		Navigation::redirect("admin/menu_top");
		exit();
	}		
	private function createFormData($ReturnResultVO = NULL){
		//adiciona o content na url de envio do formulario
		if($this->content_id > 0){
			$this->my_action .= "/id.$this->content_id/";
		}

		$formData = new ContentFormView($this->ContentSiteVO, Config::getRootPath($this->my_action));
		
		$ImageFormView = new ImageFormView();
		$ImageFormView->setFormLabel("Selecionar Imagem");
		$ImageFormView->setQuantity(0);

//		$ImageFormView->setDescription(array('label'=>Translation::text('Link'), 'visible'=>false, 'type'=>'simpleText'));
		
		$FileFormView = new FileFormView();
		$FileFormView->setFormLabel("Selecionar Arquivo");
		$FileFormView->setQuantity(0);
		
//		$formData->setPersonalInput(array("name"=>"destaque[]","label"=>"Mostrar na Home?", "options"=>array((object) array("name"=>"Palestrantes", "id"=>"33"), (object) array("name"=>"Outros Palestrantes", "id"=>"34"))));

		//trocando o rótulo para Content
//		$formData->setContent(array("label"=>"Descrição:"));
		//trocando o rótulo para Title
		$formData->setTitle(array("label"=>"Titulo:"));
		$formData->setHat(array("label"=>"Url:"));
		$formData->setCategory(array("visible"=>FALSE,"name"=>"category[]","selected"=>array($this->category_id)));
		
		$formData->setImage($ImageFormView);
		$formData->setFile($FileFormView);
		
		$formData->setMassiveAttr('visible', FALSE, array(
														'Name',
//														'Title',
//														'Hat',
														'Description',
														'Content',
														'Author',
														'TemplateUrl',
														'Slug',
														'KeyWords',
														'Date',
														'DateIn',
														'DateOut',
//														'Order'
														));
		
		$formData->setActive(array("value"=>"1", "visible"=>FALSE));	
		$this->my_redirect = "admin/menu_top/select/";

		parent::edit($formData, TRUE, NULL, $this->my_redirect, NULL,  $ReturnResultVO);
	}	


	public function commit(){
//				echo "cococ";exit();
		$this->redirect_with_id = FALSE;
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