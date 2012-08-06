<?php

define('DEV', true);

include_once "library/facil3/core/BaseConfig.class.php";

//inclue classe para inserir as rotas
include_once("library/facil3/core/http/rewrite/RewriteVO.class.php");
//inclue classe para inserir as regras nas rotas
include_once("library/facil3/core/http/rewrite/RewriteRuleVO.class.php");


class Config extends BaseConfig{
	const TRANSACTION_TYPE_TRANSFER         = 'Transferência';
	
	const USER_TYPE_COMMON					= 4;
	const USER_TYPE_AFFILIATE_ARQUITETO 	= 3;
	const USER_TYPE_AFFILIATE_SITE			= 2;
	
	const USE_DATA_BASE 		= TRUE;
	const DATA_BASE_DRIVER 		= "mysql";
	const DATA_BASE_SERVER 		= "localhost";
	const DATA_BASE_NAME 		= "teto";
	const DATA_BASE_USER 		= "root";
	const DATA_BASE_PASSWORD	= "";
	
	const USE_QUERY_CACHE 		= TRUE;
	const QUERY_CACHE_FOLDER 	= "query_cache/";
	const QUERY_CACHE_TIME 		= 60;//em segundos
	
	const URL_DEFAULT_CONTROLLER	= "library/site/controller/RootController.class.php";
	const FOLDER_APPLICATION		= "library/site/";
	const FOLDER_REQUEST_CONTROLER	= "library/site/controller/";
	const SYSTEM_MAIL_FROM    = "contato@tetomc.com.br";

	const SYSTEM_MAIL_SMTP    = "smtp.fernandoschroeder.com";
	const SYSTEM_MAIL_LOGIN    = "ana@fernandoschroeder.com";
	const SYSTEM_MAIL_PASSWORD   = "q1w2e3";
	
	
	/**
	 * @var string caso não seja setada locale, essa é a locale padrão
	 */
	const DEFALT_LOCALE				= "pt_BR";
	/**
	 * @var int valor em segundos em que uma sessão de usuário logado expira
	 */
	const TIME_LOGIN				= 3600;
	
	static $FOLDER_USER_INFO 		= "outra";
	private static $array_modules_info;
	public static $DB_LINK;	// para conexao com o banco
	/**
	 * pasta root do padrão de rota
	 * 
	 * @var string
	 */
	const FOLDER_ROUTES            			= "library/site/routes/";
	const CART_MODE                			= "SESSION";
	const PRUCHASE_ORDER_EXPIRATION_TIME 	= 3; // number of DAYS
	const DEMOCRART_CEP  		   			= '04532001';	
}


//futuramente passar isso para a controler iniciar
include_once "library/facil3/core/acl/UserClient.php";


Config::setFolderView("view/site/");
Config::setRootApplication("teto");
Config::setRootPath("http://localhost/teto/");
Config::setAliasFolder("teto/");

Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "image");
Config::addModuleInfo("library/facil3/core/modules/user/dao/UserDAO.class.php", "UserDAO", "user");
Config::addModuleInfo("library/facil3/core/modules/address/dao/AddressDAO.class.php", "AddresDAO", "address");
Config::addModuleInfo("library/facil3/core/modules/file/dao/FileDAO.class.php", "FileDAO", "facil_file");
Config::addModuleInfo("library/facil3/core/modules/file/dao/FileDAO.class.php", "FileDAO", "file");
Config::addModuleInfo("library/site/modules/content/dao/ContentSiteDAO.class.php", "ContentSiteDAO", "content");

Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "dimensions");
Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "video");
Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "360");
Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "gallery");
Config::addModuleInfo("library/facil3/core/modules/image/dao/ImageDAO.class.php", "ImageDAO", "tagged");

Config::addModuleInfo("library/site/modules/content/dao/ContentSiteDAO.class.php", "ContentSiteDAO", "newslatter");


Session::start();
