<?php 
/**
 * @author		: Renato Miawaki
 * @date		: 05/8/2012
 * @version		: 1.0
 * @description	: Objeto Padrão para interface entre controllers e views
 **/
include_once "library/facil3/navigation/http/HttpContent.class.php";
class DefaultPage extends HttpContent{
	public function __construct(){
		parent::__construct();
		$this->http_header->setTitle("Fácil 3 - Titulo padrao do meu site");
		$this->http_header->setDescription("fácil 3 - descrição padrão");
		$this->http_header->setKeywords("facil 3 palavras chave");
	}
}
