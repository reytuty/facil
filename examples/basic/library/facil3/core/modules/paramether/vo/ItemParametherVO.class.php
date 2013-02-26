<?php
/*
 * @autor		: Mauricio Amorim
 * @data		: 24/12/2008
 * @versao		: 1.0
 * @comentario	: Classe fora do padro para trabalhar com os paramethers.
 */
/*
no existe tabela de paramether_item, ela  apenas um objeto de apoio criado pela ParametherDAO
	
		id
		table
		table_id
		name
		type
		varchar
		text
		number
		int
		date
		active
		locale
		
*/
class ItemParametherVO{
	public $id;//id na table paramether
	public $table;
	public $table_id;
	public $name;
	public $type;
	public $varchar;
	public $text;
	public $number;
	public $int;
	public $date;
	public $json;
	public $active;
	public $locale;
	public $status;//status � pra ver se � novo, alterado, ok
	
	const STATUS_OK 		= "ok";
	const STATUS_INSERT		= "insert";
	const STATUS_UPDATE		= "update";
	const STATUS_DELETE		= "delete";
		

	function __cosntruct(){
		//
	}
	public function setId($id){
		$this->id = DataHandler::forceInt($id);
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
	 * @param $active (int)
	 * @return void
	 */
	public function setActive($active){
		if($active !== NULL){
			$this->active = DataHandler::forceInt($active);
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
		switch(strtolower($type)){
			case ParametherDAO::TYPE_NUMBER:
				$this->type = ParametherDAO::TYPE_NUMBER;
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
		if($int !== NULL){
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
		if($date !== NULL){
			$this->date = DataHandler::convertDateToBrazil($date);
		}
	}
	
	/**
	 * @param str
	 */
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
	//---------------------[GETs]
	public function getId(){
		return $this->id;
	}
	/**
	 * @return $table_id (int)
	 */
	public function getTableId(){
		return $this->table_id;
	}
	/**
	 * @return $active (int)
	 */
	public function getActive(){
		return $this->active;
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
	
}
?>