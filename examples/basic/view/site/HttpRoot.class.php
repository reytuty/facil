<?php 
/**
 * @author		: Renato Miawaki
 * @date		: 05/8/2012
 * @version		: 1.0
 * @description	: Objeto Padrão para interface entre controllers e views
 **/
include_once("library/site/http/DefaultPage.class.php");
class HttpRoot extends DefaultPage{
	public $arrayVariable;
	public $arrayMenu;
	public function __construct(){
		parent::__construct();
		//reset de valores para páginas específicas
		//$this->http_header->setTitle(Translation::text("title aqui"));
		//$this->http_header->setDescription(Translation::text("description"));
		//$this->http_header->getKeywords(Translation::text("keyword"));
	}
}