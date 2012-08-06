<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class Menu{
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* (non-PHPdoc)
	 * @see library/facil3/core/controller/interface/HTTPControllerInterface#init()
	 */
	public function categories($return_js = TRUE){
		Config::getConection();
		$DAO = new CategoryDAO();
		$result = $DAO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 2, 1);
		//prepara
		if($result->success){
			$this->prepareCategories($result->result);
		}
		if($return_js){
			echo "var array_categories = ".json_encode($result->result).";";
			exit();
		}
		return $result;
	}
	private function prepareCategories(&$array){
		foreach($array as $category){
			$category->slug = DataHandler::strToURL($category->name);
			if(count($category->__array_category) > 0){
				$this->prepareCategories($category->__array_category);
			}
		}
	}
	public function products($return_js = TRUE){
		$DAO = ContentDAO::getInstance();
		$result = $DAO->selectByTableId("category", 2);
		if($return_js){
			echo "var array_products = ".json_encode($result->result).";";
			exit();
		}
		return $result;
	}
}