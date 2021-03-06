<?php

	/**
	 * @author		: Mauricio Amorim - eu@mauricioamorim.com.br
	 * @date		: 31/06/2010
	 * @version		: 1.0
	 * @tabela		: material
	 * @desc		: 	Adiciona state na tabela state
	 * 				
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "e_commerce/modules/state/dao/StateDAO.class.php";
include_once "facil3/core/vo/BaseVO.class.php";
include_once "facil3/interface/vo/PatternVO.class.php";


class StateVO extends BaseVO implements PatternVO{
	var $name;
	var $config;
	var $country_id;
	var $country_name;
	var $active;

	var $DAO;
	
	const TERM_NAME 		= "name";
	const TERM_CONFIG 		= "config";

	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::__construct($this);
		//para as VOs que pretendem poder ter parametro, incluir a tabela de configerencia
		$this->__table = "state";
		//se enviar $arrayFetchPost � para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->fetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		if($this->DAO == NULL){
			$this->DAO = new StateDAO();
		}
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->setId(DataHandler::getValueByArrayIndex($array_dados, "id"));
		$this->setName(DataHandler::getValueByArrayIndex($array_dados, "name"));
		$this->setConfig(DataHandler::getValueByArrayIndex($array_dados, "config"));
		$this->setCountryId(DataHandler::getValueByArrayIndex($array_dados, "country_id"));
		$this->setCountryName(DataHandler::getValueByArrayIndex($array_dados, "country_name"));
		$this->setActive(DataHandler::getValueByArrayIndex($array_dados, "active"));
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		//var_dump($obj_dados);
		//geralmente j� vem do banco para usar esse metodo
		$this->setId($obj_dados->id);
		$this->setName($obj_dados->name);
		$this->setConfig($obj_dados->config);
		$this->setCountryId($obj_dados->country_id);
		$this->setCountryName($obj_dados->country_name);
		$this->setActive($obj_dados->active);
	}
	//---------------SETs
	/*
	 *  $active int
	 */
	public function setActive($active){
		if($active !== NULL){
			$this->active = DataHandler::forceInt($active);
		}
	}

	/**
	 * @param $name
	 * @param $locale
	 * 
	 */
	public function setName($name, $locale = NULL){
		if($locale == NULL){
			$this->name = DataHandler::forceString($name);
		} else {
			$this->addTherms(StateVO::TERM_NAME, $locale, $name);
		}
	}
	/**
	 * @param $config
	 * 
	 */
	public function setConfig($config, $locale = NULL){
		if($locale == NULL){
			$this->config = DataHandler::forceString($config);
		} else {
			$this->addTherms(StateVO::TERM_CONFIG, $locale, $config);
		}
	}
	/**
	 * @param $country_name
	 */
	public function setCountryId($country_id){
		if($country_id !== NULL){
			$this->country_id = DataHandler::forceInt($country_id);
		}
	}
	public function setCountryName($country_name){
		$this->country_name = DataHandler::forceString($country_name);
	}
	// ---------------------------GETs
	/**
	 * @return int
	 */
	public function getActive(){
		return $this->active;
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale == NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(StateVO::TERM_NAME, $locale);
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
		/**
	 * @return str
	 */
	public function getConfig($locale = NULL){
		if($locale == NULL){
			return $this->config;
		} else {
			$tempResult = $this->getStringTherm(StateVO::TERM_CONFIG, $locale);
			return ($tempResult == "")?$this->config:$tempResult;
		}
	}
	/**
	 * @return int
	 */
	public function getCountryId(){
		return $this->country_id;
	}
	
	/**
	 * @return str
	 */
	public function getCountryName(){
		return $this->country_name;
	}
	
	/**
	 * valida��o da VO para ver se est� apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->sucess = TRUE;
		if(!Validation::blank($this->name, 2)){
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_STATE_VALIDATE_NAME"));
		}
		if(!$this->country_id > 0){
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_STATE_VALIDATE_COUNTRY_ID"));
		}
		return $ReturnResultVO;
	}

	public function resultHandler($tempResult, &$arrayReturn){ //
		switch($tempResult){
			case DbInterface::ERROR_DUPLICATE_ENTRY:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_STATE_DUPLICATE_ENTRY");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_STATE_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_STATE_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_STATE_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_STATE_INSERT_COMMIT");
				break;
			case DbInterface::SUCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCESS_TERM");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}

?>