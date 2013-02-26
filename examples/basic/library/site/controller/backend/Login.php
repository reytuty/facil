<?php

include_once("library/facil3/core/controller/admin/DefautAdmin.php");
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));

include_once(Config::FOLDER_APPLICATION."modules/user_detail/dao/UserDetailDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/user_detail/vo/UserDetailVO.class.php");
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Login{
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	private $DAO;
	protected $to;
	public function __construct($arrayRestFolder){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
		$this->to = ""; 
		//Debug::print_r($arrayRestFolder);
		if(count($arrayRestFolder) > 0 && $arrayRestFolder[0] == "to"){
			for($i = 1; $i < count($arrayRestFolder); $i++){
				$this->to .= $arrayRestFolder[$i]."/";
			}
		}
	}
	
	public function init($_returnResult = null){
		//n�o enviou nada ,s� quer logar
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$retornoDaPaginaHTML->to 			= $this->to;
		$retornoDaPaginaHTML->returnResult 	= $_returnResult;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		
		return $returnResult;
	}
	public function check(){
		//busca o post
		$login		= DataHandler::getValueByArrayIndex($_POST, "login");
		$password 	= DataHandler::getValueByArrayIndex($_POST, "password");
		
		//iniciando o retorno padrao em http client ajax
		$returnResult = new ReturnResultVO();
		
		//se não validar retornar erro 
		if(!Validation::blank($password, 1) && !Validation::blank($login, 1)){
			$returnResult->success = 0;
			$returnResult->message = "Envie Login e Senha" ;
		}else{
			//cadastra o usuario e os detalhes do mesmo
			$DAO = UserDetailDAO::getInstance();
			$ReturnDataVO = $DAO->checkLogin($login, $password);
			//print_r($ReturnDataVO);
			if($ReturnDataVO->success && count($ReturnDataVO->result)>0){
				if(($ReturnDataVO->result[0]->user_type_id == Config::ADMIN_USER_TYPE_ID || $ReturnDataVO->result[0]->user_type_id == Config::DEVELOPER_USER_TYPE_ID)){
					UserClient::setId($ReturnDataVO->result[0]->id);
					UserClient::setTypeId($ReturnDataVO->result[0]->user_type_id);
					UserClient::setActiveTime(time());
					//setando sucesso true caso tenha dado certo
					$returnResult->success = $ReturnDataVO->success;
					$returnResult->message = "sucesso ao logar";
				}else{
					//print_r($ReturnDataVO);
					$returnResult->success = 0;
					$returnResult->message = "Usuario sem permissão!";
				}
			}else{
				//print_r($ReturnDataVO);
				$returnResult->success = 0;
				$returnResult->message = "Erro ao Logar!";
			}
		}
		//Debug::print_r($returnResult); exit();
		if($returnResult->success){
			if($this->to != ""){
				Navigation::redirect($this->to);
			} else {
				Navigation::redirect("backend");
			}
		} else {
			return $this->init($returnResult);
		}
	}
	
	public function logout(){
		UserClient::kill();
		Navigation::redirect("");
	}
}