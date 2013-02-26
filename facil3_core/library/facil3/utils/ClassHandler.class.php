<?php
/**
 * @author 	Renato Miawaki
 * @desc 	Classe para verificação de classes ou objetos
 */
class ClassHandler {
	/**
	 * @param $object object
	 * @param $method_name string
	 * @return bool
	 */
	public static function isMethodPrivate($object, $method_name){
		//primeiro ve se o methodo existe
		if(method_exists($object, $method_name)){
			$arrayMethods = get_class_methods($object);
			foreach($arrayMethods as $_method){
				if($_method == $method_name){
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	/**
	 * @param $object object
	 * @param $method_name string
	 * @return bool
	 */
	public static function isMethodPublic($object, $method_name){
		return !ClassHandler::isMethodPrivate($object, $method_name);
	}
}