<?php 
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
include_once "library/site/modules/content/dao/ContentSiteDAO.class.php";
include_once "library/facil3/core/dao/LinkDAO.class.php";
include_once "library/facil3/core/modules/content/ContentFormView.class.php";
include_once "library/facil3/core/modules/content/ContentSelectView.class.php";
include_once "library/site/controller/admin/default/GenericAdminController.class.php";
include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";
include_once "library/facil3/core/modules/image/vo/ImageVO.class.php";
/**
 * @author 		Renato Seiji Miawaki
 * @desc  		para editar informações de uma imagem
 */
class Image extends GenericAdminController{
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
	}
	/**
	 * /backend/image/update/id.N/
	 * e por post enviar descricao
	 * @return void da echo de string
	 */
	public function update(){
		$ReturnResultVO = new ReturnResultVO();
		
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$descricao = DataHandler::forceInt(DataHandler::getValueByArrayIndex($_POST, "descricao"));
		if($id > 0){
			$ImageVO = new ImageVO();
			$ReturnResultVO = $ImageVO->setId($id, TRUE);
			if($ReturnResultVO->success){
				$ImageVO->setDescription($descricao);
				$ReturnResultVO = $ImageVO->commit();
			}
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
}
