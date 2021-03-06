<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela paramether
	 * @type		: 	
	 * 					A tabela paramether
	 *  
								id,
								table,
								table_id,
								name,
								type,
								varchar,
								text,
								number,
								int,
								date,
								active
								locale

	 *
	 * @obs			: utiliza a tabela paramether para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/paramether/dao/ParametherDAO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";

class ParametherVO extends BaseVO implements PatternVO{
	
	public $id;
	public $table;
	public $table_id;
	public $name;
	public $type;
	public $varchar;
	public $text;
	public $number;
	public $int;
	public $date;
	public $active;
	public $locale;
	
	public $status;

	
	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct();
		$this->__table = "paramether";
		//se enviar $arrayFetchPost é para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = ParametherDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		$this->table = DataHandler::getValueByArrayIndex($array_dados, "table");
		$this->table_id = DataHandler::getValueByArrayIndex($array_dados, "table_id");
		$this->name = DataHandler::getValueByArrayIndex($array_dados, "name");
		$this->type = DataHandler::getValueByArrayIndex($array_dados, "type");
		$this->locale = DataHandler::getValueByStdObjectIndex($obj_dados, "locale");
		$this->varchar = DataHandler::getValueByStdObjectIndex($obj_dados, "varchar");
		$this->text = DataHandler::getValueByStdObjectIndex($obj_dados, "text");
		$this->number = DataHandler::getValueByStdObjectIndex($obj_dados, "number");
		$this->int = DataHandler::getValueByArrayIndex($array_dados, "int"); 
		$this->date = DataHandler::convertDateToDB(DataHandler::getValueByArrayIndex($array_dados, "date")); 
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->table_id = DataHandler::getValueByStdObjectIndex($obj_dados, "table_id");
		$this->table = DataHandler::getValueByStdObjectIndex($obj_dados, "table");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->name = DataHandler::getValueByStdObjectIndex($obj_dados, "name");
		$this->type = DataHandler::getValueByStdObjectIndex($obj_dados, "type");
		$this->locale = DataHandler::getValueByStdObjectIndex($obj_dados, "locale");
		$this->varchar = DataHandler::getValueByStdObjectIndex($obj_dados, "varchar");
		$this->text = DataHandler::getValueByStdObjectIndex($obj_dados, "text");
		$this->number = DataHandler::getValueByStdObjectIndex($obj_dados, "number");
		$this->int = DataHandler::getValueByStdObjectIndex($obj_dados, "int"); 
		$this->date = DataHandler::convertDateToDB(DataHandler::getValueByStdObjectIndex($obj_dados, "date")); 
	}
	
	/**
	 * @param $table_id (int)
	 * @return void
	 */
	public function setTableId($table_id){
		if($table_id !== NULL){
			$this->table_id = DataHandler::forceInt($table_id);
		}
	}
	/**
	 * @param $table (str)
	 * @return void
	 */
	public function setTable($table){
		if($table !== NULL){
			$this->table = DataHandler::forceString($table);
		}
	}
	/**
	 * @param $name (str)
	 * @return void
	 */
	public function setName($name){
		$this->name = DataHandler::forceString($name);
	}
	/**
	 * @return str
	 */
	public function setLocale($locale){
		$this->locale = $locale;
	}
	/**
	 * @param $type
	 * @param $value
	 * @return void
	 */
	public function setType($type){
		//echo "vai comparar ".strtolower($type)." com :".$this->TYPE_INT;
		switch(strtolower($type)){
			case ParametherDAO::TYPE_NUMBER:
				$this->type = $value;
				break;
			case ParametherDAO::TYPE_TEXT:
				$this->type = ParametherDAO::TYPE_TEXT;
				break;
			case ParametherDAO::TYPE_JSON:
				$this->type = ParametherDAO::TYPE_JSON;
				break;
			case ParametherDAO::TYPE_INT:
				$this->type = ParametherDAO::TYPE_INT;
				break;
			case ParametherDAO::TYPE_DATE:
				$this->type = ParametherDAO::TYPE_DATE;
				break;
			default:
				$this->type = ParametherDAO::TYPE_VARCHAR;
				break;
		}
	}
	/**
	 * @param $value
	 * @return void
	 */
	public function setValue($value){
		//echo "vai comparar ".strtolower($type)." com :".$this->TYPE_INT;
		$this->setValueByType($value, $this->type);
	}
	/**
	 * @param $type
	 * @param $value
	 * @return void
	 */
	public function setValueByType($value, $type){
		//echo "vai comparar ".strtolower($type)." com :".$this->TYPE_INT;
		switch(strtolower($type)){
			case ParametherDAO::TYPE_NUMBER:
				$this->number = $value;
				break;
			case ParametherDAO::TYPE_TEXT:
			case ParametherDAO::TYPE_JSON:
				$this->text = $value;
				break;
			case ParametherDAO::TYPE_INT:
				$this->int = $value;
				break;
			case ParametherDAO::TYPE_DATE:
				$this->date = DataHandler::convertDateToDB($value);
				break;
			default:
				$this->varchar = $value;
				break;
		}
	}
	/**
	 * @param $varchar (str)
	 * @return void
	 */
	public function setVarchar($varchar){
		$this->varchar = DataHandler::forceString($varchar);
	}
	/**
	 * @param $text (str)
	 * @return void
	 */
	public function setText($text){
		$this->text = DataHandler::forceString($text);
	}
	/**
	 * @param $number (number)
	 * @return void
	 */
	public function setNumber($number){
		if($number !== NULL){
			$this->number = DataHandler::forceNumber($number);
		}
	}
	/**
	 * @param $int (int)
	 * @return void
	 */
	public function setInt($int){
		if($int !== NULL){
			$this->int = DataHandler::forceInt($int);
		}
	}
	/**
	 * @param $date (date)
	 * @return void
	 */
	public function setDate($date){
		$this->date = DataHandler::convertDateToDB($date);
	}
	public function setStatus($str){
		switch(strtolower($str)){
			case ItemParametherVO::STATUS_OK:
				$type = ItemParametherVO::STATUS_OK;
				break;
			case ItemParametherVO::STATUS_INSERT:
				$type = ItemParametherVO::STATUS_INSERT;
				break;
			case ItemParametherVO::STATUS_DELETE:
				$type = ItemParametherVO::STATUS_DELETE;
				break;
			case ItemParametherVO::STATUS_UPDATE:
			default:
				$type = ItemParametherVO::STATUS_UPDATE;
				break;
		}
		$this->status = $str;
	}
	
	// ------------------------------------------------------   GETs
	/**
	 * @return $table_id (int)
	 */
	public function getTableId(){
		return $this->table_id;
	}

	/**
	 * @return $table (str)
	 */
	public function getTable(){
		return $this->table;
	}
	/**
	 * @return $name (str)
	 */
	public function getName(){
		return $this->name;
	}
	/**
	 * @return $locale (str)
	 */
	public function getLocale(){
		return $this->locale;
	}
	/**
	 * @return $type (str)
	 */
	public function getType(){
		return $this->type;
	}
	/**
	 * @return value
	 */
	public function getValue(){
		return $this->getValueByType($this->type);
	}
	/**
	 * @param $type (str)
	 * @return value
	 */
	public function getValueByType($type){
		//echo "vai comparar ".strtolower($type)." com :".$this->TYPE_INT;
		switch(strtolower($type)){
			case ParametherDAO::TYPE_NUMBER:
				return $this->number;
				break;
			case ParametherDAO::TYPE_TEXT:
				return $this->text;
				break;
			case ParametherDAO::TYPE_INT:
				return $this->int;
				break;
			case ParametherDAO::TYPE_DATE:
				return DataHandler::convertDateToBrazil($this->date);
				break;
			default:
				return $this->varchar;
				break;
		}
	}
	/**
	 * @return $varchar (str)
	 */
	public function getVarchar(){
		return $this->varchar;
	}
	/**
	 * @return $text (str)
	 */
	public function getText(){
		return $this->text;
	}
	/**
	 * @return $number (number)
	 */
	public function getNumber(){
		return $this->number;
	}
	/**
	 * @return $int (int)
	 */
	public function getInt(){
		return $this->int;
	}
	/**
	 * @return $date (date)
	 */
	public function getDate(){
		return DataHandler::convertDateToBrazil($this->date);
	}
	/**
	 * @return $status (str)
	 */
	public function getStatus(){
		return $this->status;
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
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_PARAMETHER_VALIDATE_NAME"));
		}
		return $ReturnResultVO;
	}

	public function resultHandler($tempResult, &$arrayReturn){ //
		switch($tempResult){
			case DbInterface::ERROR_DUPLICATE_ENTRY:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_PARAMETHER_DUPLICATE_ENTRY");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_PARAMETHER_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_PARAMETHER_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_PARAMETHER_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_PARAMETHER_INSERT_COMMIT");
				break;
			case DbInterface::SUCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCESS_PARAMETHER");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}