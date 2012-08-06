<?php

include_once("library/facil3/core/controller/admin/DefautAdmin.php");
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/client/HttpClientAjax.class.php"));

include_once(Config::FOLDER_APPLICATION."modules/user_detail/dao/UserDetailDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/user_detail/vo/UserDetailVO.class.php");

/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Admin extends DefautAdmin implements HTTPControllerInterface{
	protected $arrayRestFolder 	= array();
	protected $arrayVariable 		= array();
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
		parent::__construct();
	}
	public function init(){	
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		
		return $returnResult;
	}
}