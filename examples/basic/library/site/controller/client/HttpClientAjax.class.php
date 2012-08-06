<?php 
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Objeto Padrão de retorno para requisições ajax
 **/
class HttpClientAjax{
	public $success = 0;
	public $response = "";
	public $message = "";

	public $username = "";
	public $access_last = "";
	
	public function __construct(){
	}

	/**
	 * @return objeto em json com os parametros success, result, message))
	 */
	public function convertToJson(){
		return json_encode(array("success"=>$this->success, "response"=>$this->response, "message"=>$this->message));
	}
	
	public function setFecthReturnResultVO($ReturnResultVO){
		$this->success = ($ReturnResultVO->success)?1:0;
		$this->response  = $ReturnResultVO->result;
		$tempMessage = "";
		foreach($ReturnResultVO->array_messages as $message){
			$tempMessage.= $message." ";
		}
		$this->message = $tempMessage."";
	}

	public function setFecthReturnDataVO($ReturnDataVO){
		$this->success = ($ReturnDataVO->success)?1:0;
		$this->response  = $ReturnDataVO->result;
		$this->message  = $ReturnDataVO->result;
	}
}