<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once 'library/facil3/core/modules/category/dao/CategoryDAO.class.php';
include_once("".Config::FOLDER_APPLICATION."/controller/Admin.php");
class Unidades extends Admin implements HTTPControllerInterface{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();

	private $DAO;
	//id da categoria 
	private $category_id = 19;
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
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
	public function select(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		$resultPage = new DefaultPage();
		$resultPage->__array_category = $this->getCategoryCascade($this->category_id);
		//adicionando ContentVO na lista de category
		$LinkDAO = LinkDAO::getInstance();
			
		//Debug::print_r($resultPage->__array_category);exit();
		foreach($resultPage->__array_category as $level1){
			foreach($level1->__array_category as $level2){
				//Debug::print_r($level2);
				$level2->__array_category = array();
				//agora lista os contents e poe aqui como se fosse categoria
					$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $level2->id, "content", NULL, 1);
					//verifica se o resultado é uma categoryVO
					if($returnDataVO->success && count($returnDataVO->result)>0){
						foreach($returnDataVO->result as $LinkVO){
							$tempReturnDataVO = $LinkVO->getLinkedVO();
							//Debug::print_r($tempReturnDataVO);exit();
							if($tempReturnDataVO->success){
								//Debug::print_r($tempReturnDataVO->result);
								$std = new stdClass();
								$std->id 			= $tempReturnDataVO->result->id;
								$std->name 			= $tempReturnDataVO->result->title;
								$std->category_id	= $level2->id;
								$level2->__array_category[] = $std;
							}
						}
						//exit();
					}
				
			}
		}
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
	public function insertCidade($CategoryVO = NULL){
		if(!$CategoryVO){
			$CategoryVO = new CategoryVO();
		}
//		echo $this->module;
		$returnResult = new HttpResult();
		$resultPage = new HttpContent();
		$resultPage->vo = $CategoryVO;
		
		$DAO = CategoryDAO::getInstance();
		if(FALSE){
			$DAO = new CategoryDAO();
		}
		$listReturnDataVO = $DAO->select(CategoryDAO::RETURN_STD_OBJECT, NULL, 1, 19);
		
		$resultPage->__array_category = array();
		
		if($listReturnDataVO->success){
			if(count($listReturnDataVO->result)>0){
				$resultPage->__array_category = $listReturnDataVO->result;
			}
		}
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
	}
	/**
	 * @return HttpResult
	 */
	public function insert($CategoryVO = NULL){
		if(!$CategoryVO){
			$CategoryVO = new CategoryVO();
		}
//		echo $this->module;
		$returnResult = new HttpResult();
		$resultPage = new HttpContent();
		$resultPage->vo = $CategoryVO;
		$resultPage->__array_category = $this->getCategoryCascade(0);
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
	}
	/**
	 * @return HttpResult
	 */
	public function insertEstado($CategoryVO = null){
		if(!$CategoryVO){
			$CategoryVO = new CategoryVO();
		}
		$returnResult 		= new HttpResult();
		$resultPage 		= new HttpContent();
		$resultPage->category_id 	= $this->category_id;
		$resultPage->vo 	= $CategoryVO;
		//nao vai nada como categoria pra selecionar, nao existe isso
		$resultPage->__array_category = array();
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
	}
	/**
	 * @return HttpResult
	 */
	public function insertUnidade($ContentVO = null){
		if(!$ContentVO){
			$ContentVO = new ContentVO();
		}
		$returnResult 		= new HttpResult();
		$resultPage 		= new HttpContent();
		$resultPage->category_id 	= $this->category_id;
		$resultPage->vo 	= $ContentVO;
		//nao vai nada como categoria pra selecionar, nao existe isso
		$resultPage->__array_category = array();
		$returnResult->setHttpContentResult($resultPage);
		return $returnResult;
	}
	public function edit(){
		$category_id 	= DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		$type_edit 		= $this->arrayRestFolder[1];
		$CategoryVO = NULL;
		if($category_id > 0){
			$CategoryVO = new CategoryVO();
			if($category_id > 0){
				$CategoryVO->setId($category_id, TRUE);
			}
			switch($type_edit){
				case "estado":
					return $this->insertEstado($CategoryVO);
					break;
				case "cidade":
					return $this->insertCidade($CategoryVO);
					break;
				case "unidade":
					//não serve pra unidade esse edit genérico pois unidade é content e não category
					break;
			}
		}
		Navigation::redirect("admin/unidades/select");
	}
	public function commit(){
		//iniciando o retorno padrao 
		$HttpReturnResult = new HttpResult();
		$CategoryVO = new CategoryVO();
		if($_POST["category_id"] == NULL){
			$_POST["category_id"] = 0;
		}
		$_POST["active"] = 1;
		$CategoryVO->setFetchArray($_POST);
		$ResultValidate = $CategoryVO->validate();
		$resultPage = new DefaultPage();
		//Debug::print_r($ResultValidate); exit();
		if($ResultValidate->success){
			$ReturnResult = $CategoryVO->commit();
			//Debug::print_r($ReturnResult);exit();
			if($ReturnResult->success){
				Navigation::redirect("admin/unidades/select");
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
		Navigation::redirect("admin/unidades/select");
	}

}