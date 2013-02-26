<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");

include_once(Config::FOLDER_APPLICATION."modules/user_detail/dao/UserDetailDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/user_detail/vo/UserDetailVO.class.php");

include_once("library/facil3/core/modules/user_address/dao/UserAddressDAO.class.php");
include_once("library/facil3/core/modules/user_address/vo/UserAddressVO.class.php");

include_once("library/facil3/core/modules/address/dao/AddressDAO.class.php");
include_once("library/facil3/core/modules/address/vo/AddressVO.class.php");

include_once("library/facil3/core/modules/state/dao/StateDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");

include_once("library/facil3/core/acl/UserClient.php");

include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";
include_once("library/facil3/utils/Mail.class.php");


include_once(Config::FOLDER_APPLICATION."controller/Admin.php");
class User implements HTTPControllerInterface{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();

	private $DAO;

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
		//nada, da pau mesmo
		exit();
	}
	
	/**
	 * @return HttpResult
	 */
	public function insert(){
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$ReturnResultVO = new ReturnResultVO();
		
		$senha 				= DataHandler::getValueByArrayIndex($_POST, "password");
		$confirm_password 	= DataHandler::getValueByArrayIndex($_POST, "confirm_password");
		
		/**
		 * Array(
		    [first_name] => fulanidio
		    [last_name] => farofeiro
		    [email] => renato@gmailfofofo
		    [company] => teste
		    [occupation] => teste prof
		    [interests] => Array
		        (
		            [0] => restaurantes
		            [1] => escritorio
		            [2] => newsletter
		        )
		    [user_type] => arquiteto
		)
		 */
		
	 	$_POST["name"] 			= $_POST["first_name"];
	 	$_POST["recive_news"] 	= (isset($_POST["interests"]) && is_array($_POST["interests"]) && in_array("recive_news", $_POST["interests"]))?1:0;
	 	$_POST["website"] 		= (isset($_POST["user_type"])) ? $_POST["user_type"]:"";
	 	//interesses gravei no rg
	 	if(isset($_POST["interests"])){
	 		$_POST["rg"]			= (isset($_POST["interests"]) && is_array($_POST["interests"]))?json_encode($_POST["interests"]):$_POST["interests"];
	 	}
		$VO = new UserDetailVO($_POST);
		if($senha != NULL){
			if($senha == NULL || $senha != $confirm_password ){
				$ReturnResultVO->success 	= FALSE;
				$ReturnResultVO->addMessage("confimação de senha incorreta");
			}else{
				$VO->setActive(0);
				$VO->setUserTypeId(0);
				$ReturnResultVO = $VO->commit(TRUE);
				if($ReturnResultVO->success){
					
				  	$smtp = new Smtp(Config::SYSTEM_MAIL_SMTP, 587) ;
					$smtp->user = Config::SYSTEM_MAIL_LOGIN ;
					$smtp->pass = Config::SYSTEM_MAIL_PASSWORD ;
					ob_start();
					$smtp->debug = true ;
					
					  // $from = "'" . $postData->sender_name . "' <" . Config::SYSTEM_MAIL_FROM . ">" ;
					  // $to = "'" . $postData->sender_name . "' <" . $postData->receiver_mail . ">" ;
					  
					  $from = Config::SYSTEM_MAIL_FROM ;
					  $to = $VO->getEmail();
					  
					  $subject = "Teto confirmação de cadastro";
					  $mensagem = file_get_contents(Config::getFolderView("/templates/email_para_cadastrado.html"));
						$mensagem = str_replace("###name", $VO->getName(), $mensagem);
						$token = $this->getTokenFromUser($VO->id, $VO->getEmail());
						//url para o cara confirmar
						$url_confirmation = Config::getRootPath("/user/confirm/id.".$VO->getId()."/mail.".$VO->getEmail()."/token.$token");
						$mensagem = str_replace("###url_confirmacao", $url_confirmation, $mensagem);
					 
						$smtp->Send($to, $from, $subject, $mensagem, "text/html");
					    ob_end_clean();
				}
			}

			if($ReturnResultVO->success){
				//Navigation::redirect("admin/user");
				$message_success = file_get_contents(Config::getAsset("templates/cadastro_sucesso.txt"));
				$ReturnResultVO->addMessage($message_success);
			}
			$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		
		} else {
			$ReturnResultVO->addMessage("envie a senha");
		}
		echo $ReturnResultVO->toJson();
		exit();
		$retornoDaPaginaHTML->user_detail_vo = $VO;
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	public function confirm(){
		$ReturnResult = new ReturnResultVO();
		$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
		$UserDetail = new UserDetailVO();
		$ResultUser = $UserDetail->setId($id, TRUE);
		if($ResultUser->success){
			if($UserDetail->getEmail() == DataHandler::getValueByArrayIndex($this->arrayVariable, "mail")){
				$token = DataHandler::getValueByArrayIndex($this->arrayVariable, "token");
				if($token == $this->getTokenFromUser($id, $UserDetail->getEmail())){
					$UserDetail->setActive(1);
					$ReturnResult = $UserDetail->commit();
				} else {
					$ReturnResult->addMessage("token incorreto");
				}
			} else {
				$ReturnResult->addMessage("email não confere com usuario");
			}
		} else {
			$ReturnResult->addMessage("usuario não encontrado");
		}
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$retornoDaPaginaHTML->result = $ReturnResult;
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	public function lembrarSenha(){
		$ReturnResultVO = new ReturnResultVO();
		$email = DataHandler::getValueByArrayIndex($_GET, "email");
		$DAO = UserDAO::getInstance();
		$ResultData = $DAO->select(UserDAO::RETURN_STD_OBJECT, 
								$id = NULL, 
								$active = NULL, 
								$user_type_id = NULL,
								$login = NULL, 
								$password = NULL,
								$email =  $email);
		
		$ReturnResultVO->success = $ResultData->success;
		if($ResultData->success){
			if(count($ResultData->result)>0){
				$userStd = $ResultData->result[0];
				
				//Debug::print_r($userStd);
				
					//======
					$smtp = new Smtp(Config::SYSTEM_MAIL_SMTP, 587) ;
					$smtp->user = Config::SYSTEM_MAIL_LOGIN ;
					$smtp->pass = Config::SYSTEM_MAIL_PASSWORD ;
					ob_start();
					$smtp->debug = true ;
					  $from = Config::SYSTEM_MAIL_FROM ;
					  $to = $VO->getEmail();
					  
					  $subject = "Teto lembrar senha";
					  $mensagem = file_get_contents(Config::getFolderView("/templates/email_para_lembrar_senha.html"));
						$mensagem = str_replace("###login", $userStd->login, $mensagem);
						$mensagem = str_replace("###senha", $userStd->password, $mensagem);
						
						$smtp->Send($to, $from, $subject, $mensagem, "text/html");
						ob_end_clean();
			}
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
	private function getTokenFromUser($id, $email){
		return md5($email.$id."dfgh");
	}
}