<?php
/*
 * @autor		: Mauricio Amorim - eu@mauricioamorim.com.br
 * @data		: 13/07/2010
 * @versao		: 1.0
 * @comentario	: Classe fora do padr�o para trabalhar com os values.
 */
/**
		 * @param $id
		 * @param $table
		 * @param $table_id
		 * @param $therm
		 * @param $locale
		 * @param $value
		 * @param $active
		
*/
class ThermVO{
	var $id;//id na table parametro
	var $table;
	var $table_id;
	var $therm;
	var $locale;
	var $value;
	var $active;
	
	var $status;
	
	function __cosntruct(){
		//
	}
	public function setId($id){
		$this->id = DataHandler::forceInt($id);
	}
	public function setTable($table){
		$this->table = DataHandler::forceString($table);
	}
	public function setTableId($tableId){
		$this->table_id = DataHandler::forceInt($tableId);
	}
	public function setTherm($therm){
		$this->therm = DataHandler::forceString($therm);
	}
	public function setLocale($locale){
		$this->locale = DataHandler::forceString($locale);
			}
	public function setValue($value){
		$this->value = DataHandler::forceString($value);
	}
	public function setActive($int){
		$this->active = DataHandler::forceInt($int);
	}
	public function setStatus($str){
		switch($str){
			case ThermDAO::STATUS_OK:
				$type = ThermDAO::STATUS_OK;
				break;
			case ThermDAO::STATUS_DELETE:
				$type = ThermDAO::STATUS_DELETE;
				break;
			case ThermDAO::STATUS_UPDATE:
				$type = ThermDAO::STATUS_UPDATE;
				break;
			case ThermDAO::STATUS_INSERT:
				default:
				$type = ThermDAO::STATUS_INSERT;
				break;
		}
		$this->status = $type;
	}

	function setFetchObject($obj_dados){
		$this->setId($obj_dados->id);//pega o id tb
		$this->setTable($obj_dados->table);
		$this->setTableId($obj_dados->table_id);
		$this->setActive($obj_dados->active);
		$this->setTherm($obj_dados->therm);
		$this->setlocale($obj_dados->locale);
		$this->setStatus(ThermDAO::STATUS_OK);
		$this->setValue($obj_dados->value);
	}
	//---------------------[GETs]
	public function getId(){
		return $this->id;
	}
	public function getTable(){
		return $this->table;
	}
	public function getTableId(){
		return $this->table_id;
	}
	public function getTherm(){
		return $this->therm;
	}
	public function getLocale(){
		return $this->locale;
	}
	public function getValue(){
		return $this->value;
	}
	public function getActive(){
		return $this->active;
	}	
	public function getStatus(){
		return $this->status;
	}
	
}
?>