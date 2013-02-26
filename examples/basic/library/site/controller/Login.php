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
	public function register(){
		Debug::print_r($_POST);
		exit();
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
	/**
	 * 
	 * usar: login/status
	 * e esperar o resultado em json, se 
	 * {success: true} é porque está logado :)
	 * e vice versa
	 */
	public function status(){
		$ReturnResult = new ReturnResultVO();
		$ReturnResult->success = (UserClient::getId() > 0);
		echo $ReturnResult->toJson();
		exit();
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
			$returnResult->addMessage("Envie Login e Senha");
		}else{
			//cadastra o usuario e os detalhes do mesmo
			$DAO = UserDetailDAO::getInstance();
			$ReturnDataVO = $DAO->checkLogin($login, $password);
			
			if($ReturnDataVO->success && count($ReturnDataVO->result)>0){
				
					UserClient::setId($ReturnDataVO->result[0]->id);
					UserClient::setTypeId($ReturnDataVO->result[0]->user_type_id);
					UserClient::setActiveTime(time());
					//setando sucesso true caso tenha dado certo
					$returnResult->success = $ReturnDataVO->success;
					
					$message_success = file_get_contents(Config::getAsset("templates/login_sucesso.txt"));
					$returnResult->addMessage($message_success);
				
			}else{
				//print_r($ReturnDataVO);
				$returnResult->success = 0;
				$returnResult->addMessage("Erro ao Logar!");
			}
		}
		if($returnResult->success){
			if($this->to != ""){
				Navigation::redirect($this->to);
			}
		}
		echo $returnResult->toJson();
		exit();
	}
	public function logout(){
		UserClient::kill();
		Navigation::redirect("");
	}
}