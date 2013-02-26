<?php
/**
 * @author		: Renato Miawaki
 * @date		: 10/02/2011
 * @version		: 1.0
 * @description	: Objeto padrão de retorno para interface de admin, para ajax ou não
 **/
include_once "facil3/navigation/http/HttpContent.class.php";
class HttpAdminGenericResult extends HttpContent{
	/** 
	 * a supresa do kinder ovo
	 * @var ReturnResultVO aqui guarda todos os resultados necessários para retorno de admin
	 */
	public $ReturnResultVO;
	/**
	 * apenas para casos de listagem, opcional
	 * @var int
	 */
	public $count_total;
	public function __construct(){
		parent::__construct();
	}
}