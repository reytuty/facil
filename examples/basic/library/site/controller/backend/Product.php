<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
include_once Config::FOLDER_APPLICATION."controller/backend/Category.php";
include_once Config::FOLDER_APPLICATION."controller/PdfDownload.php";
class Product extends Content implements HTTPControllerInterface{
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
		//categoria produtos
		$this->category_id = 2;
		$this->my_redirect = "backend/product/show";
		
		if(isset($_POST["to"])){
			$this->my_redirect = $_POST["to"];
		}
	}
	public function init(){
		$CategoryController = new Category();
		$HttpReturn = $CategoryController->show();
		$contentResult = $HttpReturn->getHttpContentResult();
		$contentResult->vo = $this->ContentSiteVO->toStdClass();
		//Debug::print_r($contentResult->vo);
		//Debug::print_r($this->ContentSiteVO->getCategoriesDad());
		return $HttpReturn;
	}
	public function setAllToHome(){
		
		$HttpReturn = parent::init();
		$contentResult = $HttpReturn->getHttpContentResult();
		foreach($contentResult->arrayContentsVO as $ContentVO){
			if(DataHandler::getValueByArrayIndex($_GET, "tira")){
				$ContentVO->setAuthor("nao");
			} else {
				$ContentVO->setAuthor("1");
			}
			$ContentVO->commit();
		}
		$Result = new ReturnResultVO();
		$Result->success = TRUE;
		echo $Result->toJson();
		//Debug::print_r($contentResult->arrayContentsVO);
		exit();
	}
	private function getGalleryType(){
		$gallery_type = DataHandler::getValueByArrayIndex($this->arrayVariable, "type");
		switch(strtolower($gallery_type)){
			case "dimensions":
				$gallery_type = "dimensions";
				break;
			case "video":
				$gallery_type = "video";
				break;
			case "360":
				$gallery_type = "360";
				break;
			case "tagged":
				$gallery_type = "tagged";
				break;
			default:
			case "gallery":
				$gallery_type = "gallery";
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
	private function updatePdf($id){
		
		$PdfDownload = new PdfDownload();
		@$PdfDownload->produto($id);
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
		$this->updatePdf(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
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
		$this->updatePdf(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		echo json_encode($array);
		exit();
	}
	/**
	 * 
	 * Usar: backend/product/delete_image/id.N/type.TYPE/
	 */
	public function deleteImage(){
		$ReturnResultVO 	= new ReturnResultVO();
		$id 				= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$product_id			= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "product_id"));
		$galleryType 		= $this->getGalleryType();
		if($id > 0){
			$LinkDAO = new LinkDAO();
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableAndLinkedTableId($galleryType, $id);
			$ReturnResultVO->success = $ReturnDataVO->success;
			//n√£o est√° tratando o tipo de erro
		} else {
			$ReturnResultVO->addMessage(Translation::text("id?"));
		}
		//echo $ReturnResultVO->toJson();
		$this->updatePdf($product_id);
		Navigation::redirect("backend/product/gallery/type.$galleryType/id.$product_id/#listview");
		exit();
	}
	/**
	 * 
	 * Usar: backend/product/delete_file/id.N/type.TYPE/
	 */
	public function deleteFile(){
		$ReturnResultVO 	= new ReturnResultVO();
		$id 				= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$product_id			= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "product_id"));
		if($id > 0){
			$LinkDAO = new LinkDAO();
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableAndLinkedTableId("file", $id);
			$ReturnResultVO->success = $ReturnDataVO->success;
			//n√£o est√° tratando o tipo de erro
		} else {
			$ReturnResultVO->addMessage(Translation::text("id?"));
		}
		Navigation::redirect("backend/product/file/id.$product_id/");
		//echo $ReturnResultVO->toJson();
		$this->updatePdf($id);
		exit();
	}
	public function show(){
		$HttpReturn = parent::init();
		$contentResult = $HttpReturn->getHttpContentResult();
		//se enviado /pdf_burn.1/ na url ele atualiza o pdf de todos os produtos
		
		
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "pdf_burn")){
			foreach($contentResult->arrayContentsVO as $ContentVO){
				if( !file_exists( "upload/pdf/" . $ContentVO->id . "/" . $ContentVO->slug . ".pdf" ) ) {
					//Debug::print_r($ContentVO);
					//die; 
					$this->updatePdf($ContentVO->id);
				}
			}
		}
		return $HttpReturn;
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
	/**
	 * @return HttpResult
	 */
	public function add(){
		//nada
		
		return;
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
		
		//iniciando o retorno padrao
		 //$_POST["active"] = 1;
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
		 }
		if(!isset($_POST["id"]) && DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$_POST["id"] = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
		 }
		 if(!isset($_POST["order"])){
		 	$_POST["order"] = NULL;
		 }
		 if(!isset($_POST["hat"])){
		 	$_POST["hat"] = "nao";
		 }
		 if(isset($_POST["nao_e_produto"])){
		 	$_POST["active"] = 2;
		 }
		 if(!isset($_POST["author"])){
		 	$_POST["author"] = "nao";
		 }
		// Debug::print_r($_POST);exit();
		$return =  parent::commit(FALSE, $gallery_type);
		
		$this->updatePdf( $return->id);
		
		if($redirect_page){
			//Navigation::redirect($this->my_redirect);
			Navigation::redirect("backend/product/change/id.".$return->id);
			exit();
		}
		return $return;
	}
	public function delete(){
		$ReturnResult = parent::delete();
		Navigation::redirect("backend/product/show/");
	}
}