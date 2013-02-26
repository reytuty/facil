<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class RootMenuTop{
	public function __construct($arrayRestFolder = NULL){
		Config::getConection();
		$this->DAO = new CategoryDAO(); 
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* (non-PHPdoc)
	 * @see library/facil3/core/controller/interface/HTTPControllerInterface#init()
	 */
	public function getMenu(){
		$ContentDAO = ContentDAO::getInstance();
		$ReturnDataVO = $ContentDAO->selectByTableId("category", 2, ContentDAO::RETURN_VO);
		return $ReturnDataVO;
	}
}