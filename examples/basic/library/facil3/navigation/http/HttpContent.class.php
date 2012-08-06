<?php
/**
 * @author		: Renato Miawaki - reytuty@gmail.com
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Utilizada como objeto padrão para VIEWS, tendo em seu conteudo informações de meta tag
 * e variaveis de retorno para o sistema.
 * Essa Classe deve extender um Objeto geralmente intitulado por "NomeDoProjetoPage.class.php" salvo
 * na pasta library, dentro da pasta do proprio projeto, onde será setado o conteúdo defaut das meta tags de views.
 *  
 **/

include_once "library/facil3/navigation/http/HttpHeader.class.php";
include_once "library/facil3/navigation/http/HttpResult.class.php";
include_once "library/facil3/navigation/http/HttpContentInterface.class.php";
class HttpContent implements HttpContentInterface{

	protected $uri;
	
	/**
	 * @var HttpHeader
	 */
	protected $http_header;
	/**
	 * @var HttpResult
	 */
	protected $http_result;
	
	public function __construct(){
		$this->startHttpHeader();
		$this->startHttpResult();
	}

	/* @var $force (boolean)
	 * @Obs se $force == NULL ou $force = TRUE inicia uma novo Obj HttpHeader na variavel $http_header
	 */
	public function startHttpHeader($force = FALSE){
		if($this->http_header == NULL || $force = TRUE){
			$this->http_header = new HttpHeader();
		}
		
	}
	/**
	 * @return HttpHeader
	 */
	public function getHeader(){
		return $this->http_header;
	}
	/* @var $force (boolean)
	 * @Obs se $force == NULL ou $force = TRUE inicia uma novo Obj HttpResult na variavel $http_result
	 */
	public function startHttpResult($force = FALSE){
		if($this->http_result == NULL || $force = TRUE){
			$this->http_result = new HttpResult();
		}
		
	}
	
	//---------------------------------------------- SETs
	/**
	 * @param $uri (str)
	 * @return void
	 */
	public function setUri($uri){
		$this->uri = $uri;
	}
	
	//----------------------------------------------- GETs	
	/**
	 * @return $uri (str)
	 */
	public function getUri(){
		return $this->uri;
	}

}
