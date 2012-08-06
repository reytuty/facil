<?php

include_once Config::FOLDER_APPLICATION."modules/newsletter/dao/NewsletterDAO.class.php"; 
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");

/**
 * para evitar que, ao tentar pegar scripts em assets, na vies, faça consultas em banco de dados
 */
class Assets implements HTTPControllerInterface{
	public function __construct($arrayRestFolder = NULL){
		//
	}
	public function init(){
		//echo 21;
	}	
}