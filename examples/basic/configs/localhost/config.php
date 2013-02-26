<?php

define('DEV', true);

include_once "library/facil3/core/BaseConfig.class.php";

//inclue classe para inserir as rotas
include_once("library/facil3/core/http/rewrite/RewriteVO.class.php");
//inclue classe para inserir as regras nas rotas
include_once("library/facil3/core/http/rewrite/RewriteRuleVO.class.php");


class Config extends BaseConfig{
	
	const USER_TYPE_COMMON					= 4;
	
	const USE_DATA_BASE 		= TRUE;
	const DATA_BASE_DRIVER 		= "mysql";
	const DATA_BASE_SERVER 		= "127.0.0.1";
	const DATA_BASE_NAME 		= "teto";
	const DATA_BASE_USER 		= "root";
	const DATA_BASE_PASSWORD	= ""; 
	public static $DB_LINK;	// para singleton de conexao com o banco
	
	
	const USE_QUERY_CACHE 		= FALSE;
	const QUERY_CACHE_FOLDER 	= "query_cache/";
	const QUERY_CACHE_TIME 		= 60;//em segundos
	
	const URL_DEFAULT_CONTROLLER	= "library/site/controller/RootController.class.php";
	const FOLDER_APPLICATION		= "library/site/";
	const FOLDER_REQUEST_CONTROLER	= "library/site/controller/";
	const SYSTEM_MAIL				= "";
	
	const SYSTEM_MAIL_FROM  		= "";
	const SYSTEM_MAIL_SMTP = "";
	const SYSTEM_MAIL_LOGIN = "";
 	const SYSTEM_MAIL_PASSWORD = "";
	
	/**
	 * @var string caso não seja setada locale, essa é a locale padrão
	 */
	const DEFALT_LOCALE				= "pt_BR";
	/**
	 * @var int valor em segundos em que uma sessão de usuário logado expira
	 */
	const TIME_LOGIN				= 600000;
	
	private static $array_modules_info;
	
	/**
	 * pasta root do padrão de rota
	 * 
	 * @var string
	 */
	const FOLDER_ROUTES            			= "library/site/routes/";
}

Config::setFolderView("view/site/");
//pastas necessarias para chegar até o index.php
Config::setRootApplication("facil/facil/examples/basic/");
//pastas necessarias para chegar até o index.php
Config::setAliasFolder("facil/facil/examples/basic");

Config::setRootPath("http://localhost/facil/facil/examples/basic/");

Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "image");
Config::addModuleInfo("library/facil3/core/modules/user/dao/UserDAO.class.php", "UserDAO", "user");
Config::addModuleInfo("library/facil3/core/modules/address/dao/AddressDAO.class.php", "AddresDAO", "address");
Config::addModuleInfo("library/facil3/core/modules/file/dao/FileDAO.class.php", "FileDAO", "facil_file");
Config::addModuleInfo("library/facil3/core/modules/file/dao/FileDAO.class.php", "FileDAO", "file");
Config::addModuleInfo("library/site/modules/content/dao/ContentSiteDAO.class.php", "ContentSiteDAO", "content");

Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "gallery");

Session::start();
