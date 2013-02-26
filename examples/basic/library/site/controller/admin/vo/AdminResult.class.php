<?php
include_once "library/facil3/navigation/http/HttpContent.class.php";

/**
 * @author 	Renato Miawaki
 * @desc	resultado para o admin
 */
class AdminResult extends HttpContent{
	public $success 		= TRUE;
	public $array_messages 	= array();
	
	public function __construct(){
		parent::__construct();
	}
}