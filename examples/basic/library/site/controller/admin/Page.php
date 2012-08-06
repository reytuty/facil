<?php
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */



class Page extends Content{
	private $my_action 		= "admin/page/commit";
	private $total_files	= 0;
	//
	protected $sub = "";
	/**
	 * @param $arrayRest
	 * @return void
	 */
	public function __construct($arrayRest = NULL){
		parent::__construct($arrayRest);
		
		$this->sub = (count($this->arrayRestFolder) > 1)?$this->arrayRestFolder[1]: "";
		
		$this->my_redirect  = "admin/page/select";
		$this->my_redirect .= "/".$this->sub;
		$this->my_action .= "/".$this->sub;
		
		//echo $this->sub;
		switch($this->sub){
			case "blog":
				$this->category_id = 36;
				
				break;
			case "sobre":
				$this->category_id = 15;
				$this->my_redirect = "/admin/page/edit/sobre/id.15/";
				break;
			case "franquias":
				$this->category_id = 42;
				$this->my_redirect = "/admin/page/edit/franquias/id.21/";
				//$this->total_files = 1;
				break;
			case "unidades":
				$this->my_redirect = "/admin/unidades/select/";
				//$this->total_files = 1;
				break;
			default:
				$this->category_id = 18;
				break;
		}
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "category_id")){
			$this->category_id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "category_id"));
		}
	}

	public function init(){
		if($this->sub == "blog"){
			$returnResult = parent::init("id", " DESC ");
		} else {
			$returnResult = parent::init();
		}
		
		
		//blog é id 36
		//$ContentVO->toStdClass(Config::getLocale());
		//Debug::print_r($this->arrayRestFolder[1]);exit();
		$SelectData = new ContentSelectView($this->arrayContentsVO);
		$SelectData->setGoToLocation(array("value"=>"admin/page"));
		
		$SelectData->gerate();
		
		$SelectData->setMassiveAttr('visible', FALSE, array(
//																'Title',
																'Name',
																'Hat',
																'Description',
																'Content',
																'Author',
																'TemplateUrl',
//																'Slug',
																'KeyWords',
																'Date',
																'DateIn',
																'DateOut',
																'Order'
																));
		
		$SelectData->title["label"] = "Titulo:";
		$SelectData->slug["label"] 	= "Url:";
		$SelectData->slug["type"]	= "url_local";//faz com que mostre a config::getRootPatc + url
		
		$SelectData = $SelectData->getFormData();
		
		$HttpContentResult = $returnResult->getHttpContentResult();
		
		$HttpContentResult->selectData = $SelectData;
		
		$returnResult->setHttpContentResult($HttpContentResult);
		$HttpResult = $returnResult;
		$HttpContentResult->sub = $this->sub;
		//para o botao de inserir
		$HttpContentResult->url_insert = Config::getRootPath("admin/page/insert/$this->sub");
		$content_sub = Config::getFolderView("admin/content/$this->sub/select.php");
		if(file_exists($content_sub)){
			include ($content_sub);exit();
		}
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
		Navigation::redirect($this->my_redirect);
		exit();
	}		
	private function createFormData($ReturnResultVO = NULL){
		//adiciona o content na url de envio do formulario
		if($this->content_id > 0){
			$this->my_action .= "/id.$this->content_id/";
		}
		//echo $this->my_action;
		
		$formData = new ContentFormView($this->ContentSiteVO, Config::getRootPath($this->my_action));
		
		
		$ImageFormView = new ImageFormView();
		$ImageFormView->setFormLabel("Selecionar Foto");
		$ImageFormView->setShowImageUrl(TRUE);
		
//		$ImageFormView->setName(array("label"=>"link", "visible"=>TRUE));
		if($this->sub == "unidades"){
			$ImageFormView->setQuantity(0);
		} else {
			$ImageFormView->setQuantity(1);
		}
//		$ImageFormView->setDescription(array('label'=>Translation::text('Link'), 'visible'=>false, 'type'=>'simpleText'));
		
		$FileFormView = new FileFormView();
		$FileFormView->setFormLabel("Selecionar Arquivo");
		$FileFormView->setQuantity($this->total_files);
		if($this->sub == "blog"){
			
			//id do blog 36
			$CategoryVO = new CategoryVO();
			$CategoryVO->setId(36, TRUE);
			$array_categorias = $CategoryVO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 1);
			$selected_category = array();
			
			$LinkDAO = LinkDAO::getInstance();
			if(FALSE){
				$LinkDAO = new LinkDAO();
			}
			if($this->ContentSiteVO->id > 0){
				$ReturnLinkCategory = $LinkDAO->select(LinkDAO::RETURN_STD_OBJECT, NULL, NULL, "content", $this->ContentSiteVO->id, 1);
				if($ReturnLinkCategory->success && $ReturnLinkCategory->count_total > 0){
					foreach($ReturnLinkCategory->result as $link_std){
						$selected_category[] = $link_std->table_id;
					}
				}
			}
			//Debug::print_r($ReturnLinkCategory);exit();
			//new ContentSiteVO();
			$formData->setCategory(array("visible"=>TRUE,"name"=>"category[]","label"=>"Categorias","selected"=>$selected_category, "options"=>$array_categorias));
		} else {
			$formData->setCategory(array("visible"=>FALSE,"name"=>"category[]","selected"=>array($this->category_id)));
		}
		if($this->sub == "unidades"){
			$formData->setContent(array("label"=>"Endereço:", "value"=>"unidade"));
		} else {
			//trocando o rótulo para Content
			$formData->setContent(array("label"=>"Descrição:"));
		}
		//trocando o rótulo para Title
		$formData->setTitle(array("label"=>"Titulo:"));
//		$formData->setHat(array("label"=>"Link:"));
		 
		$formData->setDate(array("label"=>"Data(formato: dd/mm/aaaa hh:mm:ss para 'agora' deixe vazio):"));
		$formData->setImage($ImageFormView);
		$formData->setFile($FileFormView);
		$array_to_visible_false = array('Name',
//														'Title',
														'Hat',
														'Description',
//														'Content',
														'Author',
														'TemplateUrl',
														'Slug',
														'KeyWords',
														//'Date',
														'DateIn',
														'DateOut',
														'Order');
		if($this->sub == "unidades"){
			$array_to_visible_false = array('Name',
//														'Title',
//														'Hat',
														'Description',
														'Content',
//														'Author',
														'TemplateUrl',
														'Slug',
														'KeyWords',
														'Date',
														'DateIn',
														'DateOut',
														'Order');
			$formData->setHat(array("label"=>"Endereço"));
			$formData->setAuthor(array("label"=>"Link Mapa"));
		}
		$formData->setMassiveAttr('visible', FALSE, $array_to_visible_false);
		
		$formData->setActive(array("value"=>"1", "visible"=>FALSE));	
		//$this->my_redirect = "admin/page/select/";
		parent::edit($formData, TRUE, NULL, $this->my_redirect, NULL,  $ReturnResultVO);	

	}	


	public function commit(){
		if(DataHandler::getValueByArrayIndex($_POST, "title") != NULL){
			$slug = DataHandler::removeAccent(DataHandler::getValueByArrayIndex($_POST, "title"));
			 $slug = DataHandler::removeSpecialCharacters($slug);
			 $slug = strtolower($slug);
			 $_POST["slug"] = $slug;
		}
		if(DataHandler::getValueByArrayIndex($_POST, "date") !== NULL && $_POST["date"] == ""){
			$_POST["date"] = "now()";
			
		}
		if($this->sub == "unidades"){
			$_POST["content"] = "unidades";
		}
		//Debug::print_r($_POST);
		//exit();
		//echo Debug::li($this->my_redirect);exit();
		$this->redirect_with_id = FALSE;
		if(DataHandler::getValueByArrayIndex($_POST, "category")){
			if(is_array($_POST["category"])){
				//$_POST["category"] = $_POST["category"];
				if($this->sub == "blog"){
					$_POST["category"][] = 36;
				}
				//echo Debug::print_r($_POST["category"]);
				//exit();
			} else {
				throw new Exception("envie a array de categoria. Sem isso a content nao funciona.");
			}
		}
		parent::commit();
	}
	
	
}