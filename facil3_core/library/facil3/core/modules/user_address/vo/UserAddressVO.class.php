<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela user_address
	 * @address_id		: 	
	 * 					A tabela user_address
	 *  
	  						id, 
	  						active, 
	  						address_id,
	  						user_id
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "facil3/core/modules/address/dao/AddressDAO.class.php";
include_once "facil3/core/modules/user_address/dao/UserAddressDAO.class.php";
include_once "facil3/interface/vo/PatternVO.class.php";
include_once "facil3/core/vo/BaseVO.class.php";

class UserAddressVO extends BaseVO implements PatternVO{
	
  	public $user_id;
  	public $address_id;
  	
  	public $address_std_obj;

  	public static $AddressDAO;
  	
 	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct($this);
		$this->__table = "user_address";
		//se enviar $arrayFetchPost é para distrinchar interaddress_idnte os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct

	/**
	 * inicia interaddress_idnte a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = UserAddressDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id" );
		$this->user_id = DataHandler::getValueByArrayIndex($array_dados, "user_id" ); 
		$this->address_id = DataHandler::getValueByArrayIndex($array_dados, "address_id" ); 		
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = $obj_dados->id;
		$this->user_id = $obj_dados->user_id; 
		$this->address_id = $obj_dados->address_id; 
	
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
	 * @param $user_id (int)
	 * @return void
	 */
	public function setUserId($user_id){
		if($user_id !== NULL){
			$this->user_id = DataHandler::forceInt($user_id);
		}
	}
	/**
	 * @param $address_id (str)
	 * @return void
	 */
	public function setAddressId($address_id){
		$this->address_id = DataHandler::forceInt($address_id);
	}



	// ------------------------------------------------------   GETs
	/**
	 * @return $user_id (int)
	 */
	public function getUserId(){
		return $this->user_id;
	}
	/**
	 * @return $address_id (str)
	 */
	public function getAddressId(){
		return $this->address_id;
	}
	
	//busca produtos cadastrado nesse modelo
	public function getAddress($force = false){
		if(self::$AddressDAO == NULL){
			self::$AddressDAO = new AddressDAO();
		}
		if($this->address_std_obj == NULL || $force){
			//busca produtos por ordem de mais barato
			$address = self::$AddressDAO->selectById($this->address_id);
			if($address->success && $address->result[0]->id > 0){
				$this->address_std_object = $address->result[0];
			}
		}
		return $this->address_std_object;
	}
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		///if(!$this->getAlias() > 0){
		//	$ReturnResultVO->success = FALSE;
		//	$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_ADDRESS_UPDATE_DONOT_HAVE_UPDATE"));
		//}
		return $ReturnResultVO;
	}
	
	/**
	 * @param int $tempResult
	 * @param array $arrayReturn
	 */
	public function resultHandler($tempResult, &$arrayReturn){
		switch($tempResult){
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_ADDRESS_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_ADDRESS_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_ADDRESS_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_USER_DDRESS_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_USER_ADDRESS");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}