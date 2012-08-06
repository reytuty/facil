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

include_once(Config::FOLDER_APPLICATION."controller/Admin.php");
class Client extends Admin implements HTTPControllerInterface{
	

	private $DAO;
	public $UserDatailDAO;
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function init(){
		$page = 1;
		$limit_page = 21;
		$quant_start = 0;
		
		foreach($this->arrayVariable as $variable => $value){
			if($variable == "pag" && DataHandler::forceInt($value) > 0){
				$page = DataHandler::forceInt($value);
			}
		}
		$quant_start = $page * $limit_page - $limit_page;
		
		$this->UserDatailDAO = UserDetailDAO::getInstance();
		if(false){
			$this->UserDatailDAO = new UserDetailDAO();
		}
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		$arrayUser = array();
		if(FALSE){
			$this->UserDatailDAO = new UserDetailDAO();
		}
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "busca")){
			//tem busca, vai tratar essa variavel
			$this->arrayVariable["busca"] = str_replace("busca.", "", $this->arrayRestFolder[1]);
			
		}
		if(isset($this->arrayVariable["busca"])){
 			$this->arrayVariable["busca"] = str_replace("%20", " ", $this->arrayVariable["busca"]);
		}
 		//fazendo o que precisa fazer para ter os dados
		//$ReturnDataVO = $this->UserDatailDAO->select(UserDetailDAO::RETURN_VO, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, NUll, $limit_page, $quant_start);
		/*
		<option  value="name">Nome</option>
		<option  value="email" selected="selected" >Email</option>
		<option  value="cpf">CPF</option>
		<option  value="rg">RG</option>
		<option  value="company">Empresa</option>
		<option  value="telefone">Telefone</option>
		
		*/
		$name 		= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "name")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL; 
 		$mobile 	= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "telefone")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL; 
 		$telephone 	= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "telefone")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL; 
 		$email 		= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "email")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL;
 		$rg 		= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "rg")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL;
 		$cpf 		= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "cpf")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL;
 		$company 	= (DataHandler::getValueByArrayIndex($this->arrayVariable,"filtro") == "company")?DataHandler::getValueByArrayIndex($this->arrayVariable, "busca"):NULL; 
 		
 		
		$ReturnDataVO = $this->UserDatailDAO->search(
								UserDetailDAO::RETURN_VO, 
			 					$name, 
			 					$mobile, 
			 					$telephone, 
			 					$email, 
			 					$rg, 
			 					$cpf, 
			 					$company, 
			 					$limit_page, 
								$quant_start,
								3
							);
//		Debug::print_r($ReturnDataVO);
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->arrayUsers = $ReturnDataVO->result;
		$retornoDaPaginaHTML->page = $page;
		$retornoDaPaginaHTML->limit_page = $limit_page;
		$retornoDaPaginaHTML->count_total = $ReturnDataVO->count_total;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	/**
	 * @return HttpResult
	 */
	public function insert(){
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		
		$senha 				= DataHandler::getValueByArrayIndex($_POST, "password");
		$confirm_password 	= DataHandler::getValueByArrayIndex($_POST, "confirm_password");
		
		$VO = new UserDetailVO($_POST);
		if($senha != NULL){
			if($senha == NULL || $senha != $confirm_password ){
				$ReturnResultVO = new ReturnResultVO();
				$ReturnResultVO->success 	= FALSE;
				$ReturnResultVO->addMessage("confimação de senha incorreta");
			}else{
				$VO->setActive(1);
				$VO->setUserTypeId(3);
				$ReturnResultVO = $VO->commit(TRUE);
				if($ReturnResultVO->success){
					$user_id = $ReturnResultVO->result;
					//cadastra o endereÃ§o principal
					$AddressVO = new AddressVO($_POST);
					//$AddressVO->setStateId($_POST["state_id"]);
					$tempReturnResultVO = $AddressVO->commit(TRUE);
					//print_r($tempReturnDataVO);
					//se cadastrou atualiza o user datail com o id do address cadastrado
					//se nÃ£o apaga o user datail e user  
					if($tempReturnResultVO->success){
						$address_id = $tempReturnResultVO->result;
						$VO->setId($user_id, TRUE);
						$VO->setAddressId($address_id);
						$ReturnResultVO = $VO->commit();
						if($ReturnResultVO->success){
							$UserAddressVO = new UserAddressVO();
							$UserAddressVO->setAddressId($address_id);
							$UserAddressVO->setUserId($user_id);
							$UserAddressVO->setActive(1);
	
							$ReturnResultUserAddress = $UserAddressVO->commit(); 
						}else{
							//apaga o usuario e o os detalhes do usuario
							$UserDetailDAO = UserDetailDAO::getInstance();
							$UserDetailDAO->delete($user_id);
							//apaga o endereco
							$AddressDAO = new AddressDAO();
							$AddressDAO->delete($address_id);
						}
					}else{
						$ReturnResultVO = $tempReturnResultVO;
						//apaga o usuario e o os detalhes do usuario
						$UserDetailDAO = UserDetailDAO::getInstance();
						$UserDetailDAO->delete($user_id);
					}
				}
			}

			if($ReturnResultVO->success){
				Navigation::redirect("admin/client");
				exit();
			}
			$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		
		}
		$retornoDaPaginaHTML->user_detail_vo = $VO;
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	public function update(){
		$ReturnResultVO = new ReturnResultVO();
		//iniciando o retorno padrao 
		$returnResult = new HttpResult();
		
		$senha 				= DataHandler::getValueByArrayIndex($_POST, "password");
		$confirm_password 	= DataHandler::getValueByArrayIndex($_POST, "confirm_password");
		
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");			
		}
		$VO = new UserDetailVO();
		
		$VO->setId($id, TRUE);
		
		if($_POST){
			if($senha == NULL || $senha != $confirm_password ){
				$ReturnResultVO = new ReturnResultVO();
				$ReturnResultVO->success 	= FALSE;
				$ReturnResultVO->addMessage("confimação de senha incorreta");
			}else{
				$VO = new UserDetailVO($_POST);
				$VO->setId($id);
				$ReturnResultVO = $VO->commit(TRUE);
				if($ReturnResultVO->success){
					Navigation::redirect("admin/client");
					exit();			
				}
			}
		}
		
		$arrayAddres = $VO->getAddress();
		$AdminHttpResult = new HttpAdminGenericResult();
		$ReturnResultVO->result = $VO;
		$ReturnResultVO->result->array_address = $arrayAddres;
		
		//e lá vai a VO vivinha da silva pro admin
		$AdminHttpResult->return_result_vo = $ReturnResultVO;
		$returnResult->setHttpContentResult($AdminHttpResult);
//		Debug::print_r($returnResult);
		return $returnResult;
	}
	
	
	public function delete(){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = FALSE;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");			
			$this->UserDatailDAO = UserDetailDAO::getInstance();
			$ReturnDataVO = $this->UserDatailDAO->delete($id);
			$ReturnResultVO->success	 	= $ReturnDataVO->success;
			$ReturnResultVO->result			= $ReturnDataVO->result;
		}
		echo $ReturnResultVO->toJson();
		exit();
	}

	
	//parte referente aos endereços
	
	public function insertAddress(){
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$ReturnResultVO = new ReturnResultVO();

		$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id") != NULL){
			$user_id 	= DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id");			
					
			$AddressVO = new AddressVO($_POST);
//			print_r($AddressVO);exit();
			if($_POST){
				$ReturnResultVO = $AddressVO->commit(TRUE);
				if($ReturnResultVO->success){
					$address_id = $ReturnResultVO->result;
					if(DataHandler::getValueByArrayIndex($_POST, "principal") == '1'){
						$UserDetailVO = new UserDetailVO();
						$UserDetailVO->setId($user_id, TRUE);
						$UserDetailVO->setAddressId($ReturnResultVO->result);
						$ReturnResultVO = $UserDetailVO->commit();
					}
					if($ReturnResultVO->success){
						$UserAddressVO = new UserAddressVO();
						$UserAddressVO->setActive(1);
						$UserAddressVO->setAddressId($address_id);
						$UserAddressVO->setUserId($user_id);
						$ReturnResultVO = $UserAddressVO->commit();
					}
				}
			}

			if($ReturnResultVO->success){
				Navigation::redirect("admin/client/update/id.".$user_id);
				exit();
			}
			$retornoDaPaginaHTML->return_result_vo 	= $ReturnResultVO;
			$retornoDaPaginaHTML->user_id			= $user_id;
			$retornoDaPaginaHTML->user_address_vo 	= $AddressVO;
		}else{
			Navigation::redirect("admin/client");
			exit();			
		}
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}

	public function updateAddress(){
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$ReturnResultVO = new ReturnResultVO();

		$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "address_id") != NULL && DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id") != NULL){
			$user_id 	= DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id");			
			$address_id = DataHandler::getValueByArrayIndex($this->arrayVariable, "address_id");			
							
			$AddressVO = new AddressVO($_POST);
			$UserDetailVO = new UserDetailVO();
			//			print_r($AddressVO);exit();
			if($_POST){
				$AddressVO->setId($address_id);
				$ReturnResultVO = $AddressVO->commit(TRUE);
				if($ReturnResultVO->success){
//					Debug::print_r($ReturnResultVO);
					if(DataHandler::getValueByArrayIndex($_POST, "principal") == '1'){
						$UserDetailVO->setId($user_id, TRUE);
						$UserDetailVO->setAddressId($address_id);
//						Debug::print_r($UserDetailVO);
						$ReturnResultVO = $UserDetailVO->commit();
//						Debug::print_r($ReturnResultVO);
					}
				}
				if($ReturnResultVO->success){
					Navigation::redirect("admin/client/update/id.".$user_id);
					exit();
				}
			}else{
				$AddressVO->setId($address_id, TRUE);
				$UserDetailVO->setId($user_id, TRUE);
			}

			$retornoDaPaginaHTML->return_result_vo 	= $ReturnResultVO;
			$retornoDaPaginaHTML->user_id			= $user_id;
			$retornoDaPaginaHTML->user_address_vo 	= $AddressVO;
			$retornoDaPaginaHTML->user_detail_vo 	= $UserDetailVO;
		}else{
			Navigation::redirect("admin/client");
			exit();			
		}
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	public function deleteAddress(){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = FALSE;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "address_id") != NULL && DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id") != NULL){
			$user_id 	= DataHandler::getValueByArrayIndex($this->arrayVariable, "user_id");			
			$address_id = DataHandler::getValueByArrayIndex($this->arrayVariable, "address_id");			
			
			$UserAddressDAO = UserAddressDAO::getInstance();
			$ReturnDataVO 	= $UserAddressDAO->deleteByUserAndAddress($user_id, $address_id);

			if($ReturnDataVO->success){
				$AddressDAO		= AddressDAO::getInstance();
				$ReturnDataVO 	= $AddressDAO->delete($address_id);
			}
			
			$ReturnResultVO->success	 	= $ReturnDataVO->success;
			$ReturnResultVO->result			= $ReturnDataVO->result;
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
}