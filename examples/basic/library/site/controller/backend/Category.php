<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once 'library/facil3/core/modules/category/dao/CategoryDAO.class.php';
include_once("".Config::FOLDER_APPLICATION."/controller/Admin.php");
class Category extends Admin implements HTTPControllerInterface{
	
	private $DAO;

	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function init(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		
		return $returnResult;
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function show(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		$resultPage = new DefaultPage();
		$resultPage->array_category = $this->getCategoryCascade(2);
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
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
		return $CategoryVO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 1);;
	}
	/**
	 * @return HttpResult
	 */
	public function add($CategoryVO = NULL){
		if(!$CategoryVO){
			$CategoryVO = new CategoryVO();
		}
//		echo $this->module;
		$returnResult = new HttpResult();
		$resultPage = new HttpContent();
		$resultPage->vo = $CategoryVO;
		$resultPage->array_category = array();
		
		$array_category = $this->getCategoryCascade(2);
		switch(DataHandler::getValueByArrayIndex($this->arrayVariable, "level")){
			case "1":
				$resultPage->array_category = $array_category;
				break;
			case "2":
				$temp = array();
				foreach ($array_category as $cat){
					$pre_name = "$cat->name - ";
					foreach ($cat->__array_category as $cat2){
						$cat2->name = $pre_name.$cat2->name;
						$temp[] = $cat2;
					}
					$resultPage->array_category = $temp;
				}
				break;
			default:
				$resultPage->array_category = null;
				break;
			
		}
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
	}
	public function edit(){
		$category_id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$CategoryVO = NULL;
		if($category_id > 0){
			$CategoryVO = new CategoryVO();
			$CategoryVO->setId($category_id, TRUE);
			return $this->add($CategoryVO);
		}
		Navigation::redirect("admin/category/select");
	}
	public function commit(){
		//iniciando o retorno padrao 
		$HttpReturnResult = new HttpResult();
		$CategoryVO = new CategoryVO();
		if(!isset($_POST["category_id"]) || $_POST["category_id"] == NULL){
			$_POST["category_id"] = 2;
		}
		
		$_POST["active"] = 1;
		$CategoryVO->setFetchArray($_POST);
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		if($id > 0){
			$CategoryVO->setId($id);
		}
		$ResultValidate = $CategoryVO->validate();
		$resultPage = new DefaultPage();
		//Debug::print_r($ResultValidate); exit();
		if($ResultValidate->success){
			$ReturnResult = $CategoryVO->commit();
			//Debug::print_r($ReturnResult);exit();
			if($ReturnResult->success){
				Navigation::redirect("backend/category/show");
			} else {
				$resultPage->returnResult = $ReturnResult;
			}
		} else {
			$resultPage->returnResult = $ResultValidate;
		}
		$HttpReturnResult->setHttpContentResult($resultPage);
		return $HttpReturnResult;
	}
	public function delete(){
		$DAO = CategoryDAO::getInstance();
		if(FALSE){
			$DAO = new CategoryDAO();
		}
		$DAO->delete(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		Navigation::redirect("backend/category/show");
	}

}