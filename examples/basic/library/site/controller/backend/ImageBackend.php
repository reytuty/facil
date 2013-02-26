<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once Config::FOLDER_APPLICATION."controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
include_once Config::FOLDER_APPLICATION."controller/backend/Category.php";

class ImageBackend extends Admin implements HTTPControllerInterface{
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
	}
	public function init(){
		//
	}
	public function setDescription(){
		$ReturnResult = new ReturnResultVO();
		$id 			= DataHandler::getValueByArrayIndex($_POST, "id");
		$description	= DataHandler::getValueByArrayIndex($_POST, "description");
		if($id > 0 && $segment_id > 0){
			$ImageVO = new ImageVO();
			$ImageVO->setId($id, TRUE);
			$ImageVO->setDescription($description);
			$ReturnResult = $ImageVO->commit();
		} else {
			$ReturnResult->success = FALSE;
			$ReturnResult->addMessage("id nao recebido");
		}
		echo $ReturnResult->toJson();
		exit();
	}
}