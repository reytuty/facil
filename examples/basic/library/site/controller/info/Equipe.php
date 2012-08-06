<?php

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");

include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Equipe{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();
	private $DAO;
	
	public function __construct($arrayRestFolder){
		Config::getConection();
		$this->DAO = CategoryDAO::getInstance(); 
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
			//$this->MenuController = new MenuController($this->arrayVariable);
		}
	}
	/**
	 * para listagem de produtos
	 */
	public function init(){
		//lista todos produtos vinculados a categoria 2
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$arrayContentsVO = array();
		//se foi passado o id da categoria entao vai buscar todos os contentents vinculados a mesma
		//echo Debug::li($this->category_id);exit();
			$LinkDAO = LinkDAO::getInstance();
			$category_id = 87;//categoria a que todos os produtos estão vinculados
			$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $category_id, "content", NULL, 1,NULL, NULL, $order_by = "order", $order_type = NULL);
			//verifica se o resultado é uma categoryVO
			if($returnDataVO->success && count($returnDataVO->result)>0){
				foreach($returnDataVO->result as $LinkVO){
					//Debug::print_r($LinkVO); 
					$tempReturnDataVO = $LinkVO->getLinkedVO();
					//Debug::print_r($tempReturnDataVO);exit();
					if($tempReturnDataVO->success){
						$stdClass = $tempReturnDataVO->result;
						//vo viva indo para o front
						if($stdClass->active > 0)
							$arrayContentsVO[] = $stdClass;
					}
				}
				//exit();
			}
		$retornoDaPaginaHTML->arrayContentsVO = $arrayContentsVO;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);exit();
		return $returnResult;
	}
}