<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
include_once Config::FOLDER_APPLICATION."controller/backend/Category.php";

class Family extends Content implements HTTPControllerInterface{
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
		//categoria produtos
		$this->category_id = 85;
		$this->my_redirect = "backend/client/family/show";
		if(isset($_POST["to"])){
			$this->my_redirect = $_POST["to"];
		}
	}
	public function init(){
		$CategoryController = new Category();
		$HttpReturn = $CategoryController->show();
		$contentResult = $HttpReturn->getHttpContentResult();
		$contentResult->vo = $this->ContentSiteVO->toStdClass();
		return $HttpReturn;
	}
	public function add(){
		//nada
		
		return;
	}
	public function remove(){
		$ReturnResult = new ReturnResultVO();
		
		$id 		= DataHandler::getValueByArrayIndex($_GET, "id");
		$client_id = DataHandler::getValueByArrayIndex($_GET, "client_id");
		
		if($id > 0 && $client_id > 0){
			$ContentSiteVO = new ContentSiteVO();
			$ContentSiteVO->setId($id, TRUE);
			$ContentSiteVO->removeLink("content", $client_id);
			$ReturnResult = $ContentSiteVO->commit();
		} else {
			$ReturnResult->success = FALSE;
			$ReturnResult->addMessage("id nao recebido");
		}
		echo $ReturnResult->toJson();
		exit();
	}
	public function addClient(){
		$ReturnResult = new ReturnResultVO();
		
		$id 		= DataHandler::getValueByArrayIndex($_GET, "id");
		$client_id 	= DataHandler::getValueByArrayIndex($_GET, "client_id");
		
		if($id > 0 && $client_id > 0){
			//abaixo eh a familia
			$ContentSiteVO = new ContentSiteVO();
			$ContentSiteVO->setId($id, TRUE);
			$ContentSiteVO->addLink("content", $client_id);
			$ReturnResult = $ContentSiteVO->commit();
		} else {
			$ReturnResult->success = FALSE;
			$ReturnResult->addMessage("id nao recebido");
		}
		echo $ReturnResult->toJson();
		exit();
	}
	public function change(){
		
		$HttpReturn = $this->init();
		$contentResultCategory = $HttpReturn->getHttpContentResult();
		//Debug::print_r($contentResultCategory);exit;
		//muda o id pra pegar produtos
		//$this->category_id = 2;
		$this->category_id = 43;
		$HttpReturnContent = parent::init();
		$contentResult = $HttpReturnContent->getHttpContentResult();
		$contentResult->vo = $contentResultCategory->vo;
		//agora pega a lista de produtos vinculados a essa família - ambos são contents
		//após ter o id da família, é assim que lista os produtos de uma família
		$links = $this->ContentSiteVO->getLinks("content", 1);
		$contentResult->vo->products_links = array();
		foreach($links as $link){
			$contentResult->vo->products_links[] = $link->linked_table_id;
		}
		//Debug::print_r($links);
		$contentResult->array_category = $contentResultCategory->array_category;
		
		return $HttpReturnContent;
	}
	private function getGalleryType(){
		$gallery_type = DataHandler::getValueByArrayIndex($this->arrayVariable, "type");
		switch(strtolower($gallery_type)){
			case "dimensions":
				$gallery_type = "family_dimensions";
				break;
			case "video":
				$gallery_type = "family_video";
				break;
			case "360":
				$gallery_type = "family_360";
				break;
			case "tagged":
				$gallery_type = "family_tagged";
				break;
			default:
			case "gallery":
				$gallery_type = "family_gallery";
				break;
		}
		return $gallery_type;
	}
	public function gallery(){
		$gallery_type = $this->getGalleryType();
		
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$retornoDaPaginaHTML->vo 			= $this->ContentSiteVO->toStdClass();
		$retornoDaPaginaHTML->gallery_type 	= $gallery_type;
		//agora pega a galeria selecionada
		$retornoDaPaginaHTML->array_images = $this->ContentSiteVO->getImages(NULL, $gallery_type, TRUE);
		//salvando o objeto de resultado de html no retorno
		//echo "<img src=\"".Config::getRootPath("image/get_image/image_id.".$retornoDaPaginaHTML->array_images[0]->id."/max_width.500/")."\" />";
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	public function commitGallery(){
		$gallery_type = $this->getGalleryType();
		$_FILES["image"] 	= $_FILES["files"];
		$array_image_file 	= $_FILES["files"];
		//Debug::print_r($_FILES);exit();
		$ReturnResult = $this->commit(FALSE, $gallery_type);
		//Debug::print_r($ReturnResult);exit;
		//$content_id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
		//$Content = new ContentSiteVO();
		//$Content->setId($content_id, TRUE);
		
		$array = array();
		for($i = 0; $i < count($array_image_file["name"]); $i++){
			$file_image =  new stdClass();
			$file_image->name 	= $array_image_file["name"][$i];
			$file_image->size 	= $array_image_file["size"][$i];
			$file_image->url 	= "ver a url";
			$file_image->thumbnail_url 	= "ver ";
			$file_image->delete_url 	= "";
			$file_image->delete_type 	= "";
			$array[] 					= $file_image;
		}
		echo json_encode($array);
		exit();
	}
	public function file(){
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$retornoDaPaginaHTML->vo 			= $this->ContentSiteVO->toStdClass();
		//agora pega a galeria selecionada
		$retornoDaPaginaHTML->array_files = $retornoDaPaginaHTML->vo->files;
		//Debug::print_r($retornoDaPaginaHTML->array_files);
		//salvando o objeto de resultado de html no retorno
		//echo "<img src=\"".Config::getRootPath("image/get_image/image_id.".$retornoDaPaginaHTML->array_images[0]->id."/max_width.500/")."\" />";
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	public function commitFile(){
		$gallery_type = $this->getGalleryType();
		//print_r($_FILES);exit();
		
		$_FILES["file"] 	= $_FILES["files"];
		$array_image_file 	= $_FILES["files"];
		//Debug::print_r($_FILES);exit();
		$ReturnResult = $this->commit(FALSE);
		//Debug::print_r($ReturnResult);exit;
		//$content_id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
		//$Content = new ContentSiteVO();
		//$Content->setId($content_id, TRUE);
		
		$array = array();
		for($i = 0; $i < count($array_image_file["name"]); $i++){
			$file_image =  new stdClass();
			$file_image->name 	= $array_image_file["name"][$i];
			$file_image->size 	= $array_image_file["size"][$i];
			$file_image->url 	= "ver a url";
			$file_image->thumbnail_url 	= "ver ";
			$file_image->delete_url 	= "";
			$file_image->delete_type 	= "";
			$array[] 					= $file_image;
		}
		echo json_encode($array);
		exit();
	}
	/**
	 * 
	 * Usar: backend/client/delete_image/id.N/type.TYPE/
	 */
	public function deleteImage(){
		$ReturnResultVO 	= new ReturnResultVO();
		$id 				= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$client_id			= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "client_id"));
		$galleryType 		= $this->getGalleryType();
		if($id > 0){
			$LinkDAO = new LinkDAO();
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableAndLinkedTableId($galleryType, $id);
			$ReturnResultVO->success = $ReturnDataVO->success;
			//n√£o est√° tratando o tipo de erro
		} else {
			$ReturnResultVO->addMessage(Translation::text("id?"));
		}
		Navigation::redirect("backend/client/family/gallery/type.$galleryType/id.$client_id/");
		//echo $ReturnResultVO->toJson();
		exit();
	}
	/**
	 * 
	 * Usar: backend/client/delete_file/id.N/type.TYPE/
	 */
	public function deleteFile(){
		$ReturnResultVO 	= new ReturnResultVO();
		$id 				= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$client_id			= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "client_id"));
		if($id > 0){
			$LinkDAO = new LinkDAO();
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableAndLinkedTableId("file", $id);
			$ReturnResultVO->success = $ReturnDataVO->success;
			//n√£o est√° tratando o tipo de erro
		} else {
			$ReturnResultVO->addMessage(Translation::text("id?"));
		}
		Navigation::redirect("backend/client/file/id.$client_id/");
		//echo $ReturnResultVO->toJson();
		exit();
	}
	public function show(){
		return parent::init();
	}
	/**
	 * Retorna a lista de categoria baseado na categoria pai. Não aceita nulo, se mandar nulo, nao funciona.
	 * @param int $category_dad_id
	 */
	private function getCategorysByCategoryDad($category_dad_id){
		$DAO = CategoryDAO::getInstance();
		if(FALSE){
			$DAO = new CategoryDAO();
		}
		$listReturnDataVO = $DAO->select(CategoryDAO::RETURN_VO, NULL, 1, $category_dad_id);
		return $listReturnDataVO;
	}
	private function getCategoryCascade($dad_category_id){
		$CategoryVO = new CategoryVO();
		$CategoryVO->setId($dad_category_id, TRUE);
		return $CategoryVO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 1);
	}
	public function link(){
		$CategoryController = new Category();
		$HttpReturn = $CategoryController->show();
		$contentResult = $HttpReturn->getHttpContentResult();
		$contentResult->vo = $this->ContentSiteVO->toStdClass();
		//Debug::print_r($contentResult->vo);
		//Debug::print_r($this->ContentSiteVO->getCategoriesDad());
		return $HttpReturn;
	}
	public function commit($redirect_page = TRUE, $gallery_type = "image"){
		//Debug::print_r($_POST["produtos"]);exit();
		//iniciando o retorno padrao
		 $_POST["active"] = 1;
		 if(!isset($_POST["category"]) || $_POST["category"] == NULL){
		 	$_POST["category"] = array($this->category_id);
		 } else if(!is_array($_POST["category"])){
		 	$_POST["category"] = array($_POST["category"], $this->category_id);
		 } else {
		 	//é uma array, mas melhor garantir que tem o produto id
		 	$_POST["category"][] = $this->category_id;
		 }
		 if(isset($_POST["title"])){
		 	$_POST["slug"] = DataHandler::strToURL($_POST["title"]);
		 } else {
		 	$_POST["slug"] = "";
		 }
		if(!isset($_POST["id"]) && DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$_POST["id"] = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
		 }
		 if(!isset($_POST["order"])){
		 	$_POST["order"] = NULL;
		 }
		return parent::commit($redirect_page, $gallery_type);
	}
	public function delete(){
		$ReturnResult = parent::delete();
		Navigation::redirect("backend/client/family/show/");
	}
}