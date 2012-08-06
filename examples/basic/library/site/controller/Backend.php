<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once("".Config::FOLDER_APPLICATION."/controller/Admin.php");
class Backend extends Admin implements HTTPControllerInterface{
	
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
}