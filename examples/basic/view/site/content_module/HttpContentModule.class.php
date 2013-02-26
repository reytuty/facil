<?php 
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Objeto Padrão onde são iniciados os campos utilizados para a View Product Model Details
 * @Obs 		:é extenção de uma classe com meta tags pré-definidas.
 **/
include_once Config::FOLDER_APPLICATION."http/DefaultPage.class.php";
class HttpContentModule extends DefaultPage{
	public $content_module;
	
	public function __construct(){
		parent::__construct();
		
	}
}