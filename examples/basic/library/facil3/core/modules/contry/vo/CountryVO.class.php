<?php

	/**
	 * @author		: Mauricio Amorim - eu@mauricioamorim.com.br
	 * @date		: 31/06/2010
	 * @version		: 1.0
	 * @tabela		: material
	 * @desc		: 	Adiciona country na tabela country
	 * 				
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "e_commerce/modules/country/dao/CountryDAO.class.php";
include_once "facil3/core/vo/BaseVO.class.php";
include_once "facil3/interface/vo/PatternVO.class.php";


class CountryVO extends BaseVO implements PatternVO{
	var $name;
	var $language_code;
	var $active;
	var $code;
	
	var $DAO;
	
	const TERM_NAME 		= "name";

	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::__construct($this);
		//para as VOs que pretendem poder ter parametro, incluir a tabela de configerencia
		$this->__table = "country";
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
			$this->DAO = new CountryDAO();
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
		$this->setCode(DataHandler::getValueByArrayIndex($array_dados, "code"));
		$this->setLanguageCode(DataHandler::getValueByArrayIndex($array_dados, "language_code"));
		$this->setActive(DataHandler::getValueByArrayIndex($array_dados, "active"));
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		//var_dump($obj_dados);
		$this->setId($obj_dados->id);
		$this->setName($obj_dados->name);
		$this->setCode($obj_dados->code);
		$this->setLanguageCode($obj_dados->language_code);
		$this->setActive($obj_dados->active);
	}
	//---------------SETs
	/*
	 *  $active int
	 */
	public function setLanguageCode($active){
		if($active !== NULL){
			$this->active = DataHandler::forceInt($active);
		}
	}

	/*
	 *  $active int
	 */
	public function setCode($code){
		if($code !== NULL){
			$this->code = DataHandler::forceInt($code);
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
			$this->addTherms(CountryVO::TERM_NAME, $locale, $name);
		}
	}
	public function setLanguageCode($language_code){
		$this->language_code = DataHandler::forceString($language_code);
	}
	// ---------------------------GETs

	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale == NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(CountryVO::TERM_NAME, $locale);
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
	
	/**
	 * @return str
	 */
	public function getLanguageCode(){
		return $this->language_code;
	}
	
	/**
	 * @return str
	 */
	public function getCode(){
		return $this->code;
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
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_COUNTRY_VALIDATE_NAME"));
		}
		return $ReturnResultVO;
	}

	public function resultHandler($tempResult, &$arrayReturn){ //
		switch($tempResult){
			case DbInterface::ERROR_DUPLICATE_ENTRY:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COUNTRY_DUPLICATE_ENTRY");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COUNTRY_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COUNTRY_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COUNTRY_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COUNTRY_INSERT_COMMIT");
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