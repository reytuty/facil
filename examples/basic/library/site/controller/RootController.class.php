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
class RootController{
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	
	public function __construct($arrayRestFolder){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
			//$this->MenuController = new MenuController($this->arrayVariable);
		}
	}
	public function init(){
		//busca todas as paginas cadastradas na tabela content
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
}