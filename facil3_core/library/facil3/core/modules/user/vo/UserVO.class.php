<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela user
	 * @password		: 	
	 * 					A tabela user
	 *  
	  						id, 
	  						active, 
	  						login,
	  						user_type_id,
	  						password,
	  						email, 
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/user/dao/UserDAO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";
include_once "library/facil3/core/DbInterface.class.php";


class UserVO extends BaseVO implements PatternVO{
	public $user_type_id;
  	public $login;
  	public $password;
  	public $email; 
  	
	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		$this->__table = "user";
		parent::_construct($this);
		
		//se enviar $arrayFetchPost é para distrinchar interloginnte os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia interloginnte a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = UserDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->login = DataHandler::getValueByArrayIndex($array_dados, "login");
		$this->password = DataHandler::getValueByArrayIndex($array_dados, "password");
		$this->email = DataHandler::getValueByArrayIndex($array_dados, "email"); 
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = $obj_dados->id;
		$this->user_type_id = $obj_dados->user_type_id;
		$this->active = $obj_dados->active;
		$this->login = $obj_dados->login;
		$this->password = $obj_dados->password;
		$this->email = $obj_dados->email; 
	}
	
	//---------------------------------------------------------  SETs
	
	/**
	 * @param int active
	 */
	//public function setActive($active){
	//	if($active !== NULL){
	//		$this->active = DataHandler::forceInt($active);
	//	}
	//}
	/**
	 * @param $user_type_id (int)
	 * @return void
	 */
	public function setUserTypeId($user_type_id){
		if($user_type_id !== NULL){
			$this->user_type_id = DataHandler::forceInt($user_type_id);
		}
	}
	/**
	 * @param $login (string)
	 * @param $autoSearch (boolean) = FALSE
	 * 
	 * @return void
	 */
	public function setLogin($login, $autoSearch = FALSE){
		$this->login = DataHandler::forceString($login);
		if($autoSearch){
			//vai fazer a busca automática pelo email
			$this->startDAO();
			if(FALSE){
				$this->DAO = new UserDAO();
			}
			$ReturnResult = $this->DAO->select(UserDAO::RETURN_STD_OBJECT, NULL, NULL, NULL, $login);
			//se não teve erro na query
			if($ReturnResult->success){
				//se encontrou algo
				if($ReturnResult->count_total > 0){
					//passa como parametro o retorno ( só o primeiro )
					$this->setFetchObject($ReturnResult->result[0]);
				}//não tem else
			}//end if success não tem else, se não rolar de fazer a busca automática retorna o erro
			//sucesso ou não, retorna o que aconteceu
			return $ReturnResult;
		}
	}
	/**
	 * @param $password (str)
	 * @return void
	 */
	public function setPassword($password){
		$this->password = DataHandler::forceString($password);
	}
	/**
	 * @param $email (int)
	 * @return void
	 */
	public function setEmail($email){
		$this->email = DataHandler::forceString($email);
	}
	// ------------------------------------------------------   GETs
	/**
	 * @return $user_type_id (int)
	 */
	public function getUserTypeId(){
		return $this->user_type_id;
	}
	/**
	 * @return $login (str)
	 */
	public function getLogin(){
		return $this->login;
	}
	/**
	 * @return $password (str)
	 */
	public function getPassword(){
		return $this->password;
	}
	/**
	 * @return $email (int)
	 */
	public function getEmail(){
		return $this->email;
	}
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if(!strlen($this->getLogin()) > 4){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_USER_VALIDATE_LOGIN"));
		}
		if(!strlen($this->getPassword()) > 4){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_USER_VALIDATE_PASSWORD"));
		}
		if(!Validation::validateEmail($this->getEmail())){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_USER_VALIDATE_EMAIL"));
		}
		return $ReturnResultVO;
	}
	
	/**
	 * @param int $tempResult
	 * @param array $arrayReturn
	 */
	public function resultHandler($tempResult, &$arrayReturn){
		switch($tempResult){
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_INSERT_COMMIT");
				break;
			case DbInterface::ERROR_DUPLICATE_ENTRY;
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_DUPLICATE_ENTRY");
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_USER");
				break;
			default:
				$arrayReturn[] = "erro sinistro 2($tempResult).";
				break;
		}//end switch
	}
}