<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela address
	 * @street		: 	
	 * 					A tabela address
	 *  
	  						id, 
	  						active, 
	  						alias,
	  						state_id,
	  						street,
	  						number, 
							complement,
							neighborhood,
							zip_code,
							city,
							order
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "facil3/core/modules/address/dao/AddressDAO.class.php";
include_once "facil3/interface/vo/PatternVO.class.php";
include_once "facil3/core/vo/BaseVO.class.php";

class AddressVO extends BaseVO implements PatternVO{
	
  	public $state_id;
  	public $alias;
  	public $street;
  	public $number;
  	public $complement;
	public $neighborhood;
	public $zip_code;
	public $city;
	public $order;
	public $country;
  	
 	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct($this);
		$this->__table = "address";
		//se enviar $arrayFetchPost é para distrinchar interaliasnte os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct

	/**
	 * inicia interaliasnte a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = AddressDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->state_id = DataHandler::getValueByArrayIndex($array_dados, "state_id"); 
		$this->country = DataHandler::getValueByArrayIndex($array_dados, "country"); 
		$this->state = DataHandler::getValueByArrayIndex($array_dados, "state"); 
		$this->alias = DataHandler::getValueByArrayIndex($array_dados, "alias"); 
		$this->street = DataHandler::getValueByArrayIndex($array_dados, "street"); 
		$this->number = DataHandler::getValueByArrayIndex($array_dados, "number"); 
		$this->complement = DataHandler::getValueByArrayIndex($array_dados, "complement"); 
		$this->neighborhood = DataHandler::getValueByArrayIndex($array_dados, "neighborhood"); 
		$this->zip_code = DataHandler::getValueByArrayIndex($array_dados, "zip_code"); 
		$this->city = DataHandler::getValueByArrayIndex($array_dados, "city"); 
		$this->order = DataHandler::getValueByArrayIndex($array_dados, "order"); 
		
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = $obj_dados->id;
		$this->state_id = $obj_dados->state_id; 
		$this->state = $obj_dados->state; 
		$this->country = $obj_dados->country; 
		$this->alias = $obj_dados->alias; 
		$this->street = $obj_dados->street; 
		$this->number = $obj_dados->number; 
		$this->complement = $obj_dados->complement; 
		$this->neighborhood = $obj_dados->neighborhood; 
		$this->zip_code = $obj_dados->zip_code; 
		$this->city = $obj_dados->city; 
		$this->order = $obj_dados->order; 
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
	 * @param $state_id (int)
	 * @return void
	 */
	public function setStateId($state_id){
		if($state_id !== NULL){
			$this->state_id = DataHandler::forceInt($state_id);
		}
	}
	/**
	 * @param $state (str)
	 * @return void
	 */
	public function setState($state){
		$this->state = DataHandler::forceString($state);
	}
	/**
	 * @param $country (str)
	 * @return void
	 */
	public function setCountry($country){
		$this->country = DataHandler::forceString($country);
	}
	/**
	 * @param $alias (str)
	 * @return void
	 */
	public function setAlias($alias){
		$this->alias = DataHandler::forceString($alias);
	}
	/**
	 * @param $street (str)
	 * @return void
	 */
	public function setStreet($street){
		$this->street = DataHandler::forceString($street);
	}
	/**
	 * @param $number (int)
	 * @return void
	 */
	public function setNumber($number){
		$this->number = DataHandler::forceString($number);
	}

	/**
	 * @param $neighborhood (str)
	 * @return void
	 */
	public function setNeighborhood($neighborhood){
		$this->neighborhood = DataHandler::forceString($neighborhood);
	}
	
	/**
	 * @param $zip_code (str)
	 * @return void
	 */
	public function setZipCode($zip_code){
		$this->zip_code = DataHandler::forceString($zip_code);
	}	

	/**
	 * @param $city (str)
	 * @return void
	 */
	public function setCity($city){
		$this->city = DataHandler::forceString($city);
	}	
	
	/**
	 * @param $complement (str)
	 * @return void
	 */
	public function setComplement($complement){
		$this->complement = DataHandler::forceString($complement);
	}	
	
	/**
	 * @param $order (str)
	 * @return void
	 */
	public function setOrder($order){
		$this->order = DataHandler::forceString($order);
	}	

	// ------------------------------------------------------   GETs
	/**
	 * @param $state (str)
	 * @return void
	 */
	public function getState(){
		return $this->state;
	}
	/**
	 * @param $country (str)
	 * @return void
	 */
	public function getCountry(){
		return $this->country;
	}
	/**
	 * @return $state_id (int)
	 */
	public function getStateId(){
		return $this->state_id;
	}
	/**
	 * @return $alias (str)
	 */
	public function getAlias(){
		return $this->alias;
	}
	/**
	 * @return $street (str)
	 */
	public function getStreet(){
		return $this->street;
	}
	/**
	 * @return $number (int)
	 */
	public function getNumber(){
		return $this->number;
	}
	
	/**
	 * @return $neighborhood (str)
	 */
	public function getNeighborhood(){
		return $this->neighborhood;
	}
	
	/**
	 * @return $zip_code (str)
	 */
	public function getZipCode(){
		return $this->zip_code;
	}	

	/**
	 * @return $city (str)
	 */
	public function getCity(){
		return $this->city;
	}
	/**
	 * @return $complement (str)
	 */
	public function getComplement(){
		return $this->complement;
	}		
	/**
	 * @return $order (str)
	 */
	public function getOrder(){
		return $this->order;
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
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_ADDRESS_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_ADDRESS_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_ADDRESS_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_ADDRESS_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_ADDRESS");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}