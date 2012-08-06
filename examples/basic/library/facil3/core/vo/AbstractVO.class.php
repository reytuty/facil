<?php

include_once "library/facil3/utils/DataHandler.class.php";
/**
 * Classe abstrata para VOs
 * Mas nem rolou usar na BaseVO
 * Se vc está lendo isso e tem tempo, tente aplicar lá de modo que não de erro em outras classes e no projeto
 */
class AbstractVO{
	private $__cacheObjReturn;
	//public $arrayMethodExeptionGET = array("toStdClass");
	var $arrayMethodExeptionGET = array(
		"toStdClass"	
	);
	public function __construct(){
		//echo "AbstractVO";
	}
	/**
	 * adiciona método para ser ignorado na chamada do toStdClass
	 * @param string $method_name
	 * @return void
	 */
	protected function addMethodToIgnoreList($method_name){
		$this->arrayMethodExeptionGET[] = $method_name;
	}
	/**
	 * @param string $LOCALE
	 * @return stdClass
	 */
	 public function toStdClass($LOCALE = NULL, $force = FALSE){
		if(!$force && $this->__cacheObjReturn){
			return $this->__cacheObjReturn;
		}
		$obj = new stdClass();
		$arrayMetodos = get_class_methods($this);
		//filtra os metodos do tipo get
		
		// print_r($this->arrayMethodExeptionGET);
		// exit();
		foreach($arrayMetodos as $metodo){
			if(!in_array($metodo , $this->arrayMethodExeptionGET ) && @ereg("^get", $metodo)){
				//da o nome do atributo para o mesmo nome do metodo publico get, só que sem o get
				$atributo = @ereg_replace("^get_", "", DataHandler::ecmaToUnderline($metodo));
				//pegando o valor
				if($LOCALE)
					$tempValor = $this->$metodo($LOCALE);
				else
					$tempValor = $this->$metodo();
				//adiciona o atributo no objeto de retorno
				$obj->$atributo = $tempValor;
			}
		}
		$this->__cacheObjReturn = $obj;
		return $obj;
	}
}
