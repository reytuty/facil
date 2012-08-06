<?php
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @desc		: 	VariableVO é uma unidade de Variavel para ajuda na configuração de uma DAO utilizando BaseDAO
 */
if(!isset( $WEBSERVICEACCESS ) || !$WEBSERVICEACCESS){
	echo Translation::text("LibraryLanguage::ERROR_ACCESS_WEBSERVICE");
	exit();
}
class VariableVO{
	private $name;
	public 	$value;
	private $type;
	
	public function __construct($name = NULL, $value = NULL,$type = NULL){
		$this->setName($name);
		$this->setType($type);
		$this->setValue($value);
	}
	public function setName($name = NULL){
		$this->name = DataHandler::removeSpecialCharacters($name);
	}
	public function setValue($value = NULL){
		$this->value = $value;
	}
	public function setType($type = NULL){
		switch($type){
			case "string":
				$this->type = "string";
				break;
			case "number":
				$this->type = "number";
				break;
			case "int":
				$this->type = "int";
				break;
			case "date":
			case "datetime":
			case "time":
				$this->type = "date";
				break;
			default:
				$this->type = "int";
				break;
		}
	}
	public function getName(){
		return $this->name;
	}
	public function getValue(){
		return DataHandler::forceType($this->value, $this->getType());
	}
	public function getType(){
		return $this->type;
	}
}