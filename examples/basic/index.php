<?php


error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once "library/facil3/utils/Debug.class.php";

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library/'),
    get_include_path(),
)));
//Debug::print_r($_SERVER);
//var_dump(pathinfo(__FILE__, PATHINFO_BASENAME));exit();

include_once "library/facil3/core/http/HttpRequestController.php";
$HttpRequestController = new HttpRequestController();
			

//include("teste_content.php");exit();

echo $HttpRequestController->getResult();

/*for ($i=0; $i < 1000000000000000000000000; $i++) { 
	echo('');
}*/ 

//include_once("teste_content.php");


