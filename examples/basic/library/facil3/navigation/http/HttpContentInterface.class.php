<?php
	/**
	 * @author		: Mauricio de Amorim
	 * @date		: 07/11/2010
	 * @version		: 1.0
	 * @description	: Utilizada para indicar os metodos existentes em um objeto HttpContent
	 * @Obs 		: é uma implementação da classe HttpContent.class.php
	 **/

interface HttpContentInterface{

	//protected $uri;

	/**
	 * @var HttpHeader
	 */
	//protected $http_header;
	
	/**
	 * @var HttpHeader
	 */
	//protected $http_header;
	
	function __construct();
	/**
	 * @param $force_new (bool)
	 * @return void
	 * @description:
	 * {
		 * if($http_header == NULL || $force = TRUE){
		 * 		$http_header = new HttpHeader();		
		 * }
	 * }
	 */
	function startHttpHeader($force_new = FALSE);
	
	/**
	 * @param $force_new (bool)
	 * @return void
	 * @description:
	 * {
		 * if($http_result == NULL || $force = TRUE){
		 * 		$http_result = new HttpResult();		
		 * }
	 * }
	 */
	function startHttpResult($force_new = FALSE);
	
	//*---------------------------------------------- SETs
	
	/**
	 * @param $uri (str)
	 * @return void
	 * @description:
	 *{$this->uri = $uri;}
	 */
	function setUri($uri);
	

	//*----------------------------------------------- GETs	
	

	/**
	 * @return $uri (str)
	 * @description:
	 *{return $this->uri;}
	 */
	function getUri();

}