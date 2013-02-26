<?php
include_once "library/facil3/core/vo/ReturnDataVO.class.php";

/**
 * @author 			: Renato Miawaki
 * @data			: 21/02/2011
 * @desc			: Para retorno de recursos que não vem do banco, mas se comportam como se fossem consultas no banco
 *						extends ReturnDataVO para ser tratada da mesma maneira, ou quase.
 */
class ReturnDataApiVO extends ReturnDataVO{
	
	public function __construct($success = FALSE, $result = NULL, $code_return = NULL, $query = NULL, $uniqueResult = NULL){
		parent::__construct($success, $result, $code_return, $query, $uniqueResult);
	}
	/**
	 * @param $api_result
	 * @return array de stdClass
	 */
	private function fetchObject($api_result){
		//..em andamento
	}
	/**
	 * @param $api_result
	 * @return array de array
	 */
	private function fetchArray($api_result){
		
	}
}