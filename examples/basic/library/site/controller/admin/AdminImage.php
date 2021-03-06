<?php
include_once Config::FOLDER_APPLICATION."controller/admin/default/GenericAdminController.class.php" ;
include_once "library/facil3/core/modules/image/vo/ImageVO.class.php";
/**
 * @author Renato Miawaki
 * 
 */
class AdminImage extends GenericAdminController{
	function __construct($arrayRestFolder = NULL){
		//ao chamar parent tenho certeza de que o cara ta logado
		parent::__construct($arrayRestFolder);
	}
	function delete(){
		$image_id = DataHandler::getValueByArrayIndex($_POST, "id");
		$ReturnResultVO = new ReturnResultVO();
		if($image_id > 0){
			//vai deletar
			$ImageVO = new ImageVO();
			$ImageVO->setId($image_id, TRUE);
			$ImageVO->delete();
			$LinkDAO = LinkDAO::getInstance();
			if(FALSE){
				$LinkDAO = new LinkDAO();
			}
			$ReturnDataVO = $LinkDAO->deleteAllFromLinkedTableAndLinkedTableId("image", $image_id);
			$ReturnResultVO->success = $ReturnDataVO->success;
			if($ReturnResultVO->success){
				$ReturnResultVO->addMessage(Translation::text("Image deleted successfully."));
			} else {
				$ReturnResultVO->addMessage(Translation::text("Error when deleting image."));
			}
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
}