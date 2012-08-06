<?php
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @desc		: 	Utilizada para retorno de variaveis padrão do sistema em VIEWS.
 * 					Essa Classe deve ser iniciada e manipulada obrigatóriamente apenas pela HttpContent.class.php
 * 
 * @author		: Renato Miawaki
 * @date		: 16/11/2010
 * @version		: 1.1
 * @desc		: 	Criei getAsset($relative_url) para que a view possa pegar usas assets no caminho relativo e retornar o absoluto
 * 
 * 
 * @dependences	:facil3/Config, DataHandler
 * */
include_once("library/facil3/navigation/http/HttpContent.class.php");
class HttpResult{

	protected $success;
	protected $error_code;
	protected $httpContentResult;
	protected $array_message;

	// ------------------------------------- SETs
	/**
	 * @param $resultPage (HttpContentResult)
	 * @return void
	 * talvez receba algo do tipo HttpContentInterface
	 */
	public function setHttpContentResult(HttpContent $resultPage){
		$this->httpContentResult = $resultPage;
	}
	/**
	 * @param $success (int)
	 * @return void
	 */
	public function setSuccess($success){
		$this->success = $success;
	}
	/**
	 * @param $error_code (int)
	 * @return void
	 */
	public function setErrorCode($error_code){
		$this->error_code = $error_code;
	}
	/**
	 * @param array_message (array)
	 * @return void
	 */
	public function setArrayMessage($array_message){
		$this->array_message = $array_message;
	}
	/**
	 * @param message (string)
	 * @return void
	 */
	public function addMessage($message){
		if(!$this->array_message){
			$this->array_message = array();
		}
		$this->array_message[] = $message;
	}
	
	
	// ------------------------------------- GETs
	/**
	 * @return HttpContent
	 */
	public function getHttpContentResult(){
		return $this->httpContentResult;
	}
	/**
	 * @return $success (int)
	 */
	public function getSuccess(){
		return $this->success;
	}
	/**
	 * @return $error_code (int)
	 */
	public function getErrorCode(){
		return $this->error_code;
	}
	/**
	 * @return $array_message (int)
	 */
	public function getArrayMessage(){
		return $this->array_message;
	}
	/**
	 * retorna o caminho completo, levando em conta a config da url do template
	 * @param $relative_url string
	 * @return string
	 */
	public function getAsset($relative_url){
		return DataHandler::removeDobleBars(Config::FOLDER_VIEW."/".$relative_url);
	}
}