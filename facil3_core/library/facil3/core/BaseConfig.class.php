<?php

class LocaleInfoVO{
	public $locale;
	public $name;
    
	public function __construct($p_locale = "", $p_name = ""){
		$this->name = $p_name;
		$this->locale = $p_locale;
	}
}

class ModuleInfoVO{
	public $path;
	public $name;
	public $unique_table;
    
	public function __construct($p_path = "", $p_name = "", $p_table = ""){
		$this->name = $p_name;
		$this->path = $p_path;
		$this->unique_table = $p_table;
	}
}
class BaseConfig{
	
	const ADMIN_USER_TYPE_ID 		= 1;
	const DEVELOPER_USER_TYPE_ID 	= 7;
	
	const USE_DATA_BASE 		= FALSE;
	
	const USE_QUERY_CACHE 		= FALSE;
	const QUERY_CACHE_FOLDER 	= "query_cache";
	const QUERY_CACHE_TIME 		= 0;
	
	const DATA_BASE_DRIVER 		= "";
	const DATA_BASE_SERVER 		= "";
	const DATA_BASE_NAME 		= "";
	const DATA_BASE_USER 		= "";
	const DATA_BASE_PASSWORD	= "";
	
	const URL_DEFAULT_CONTROLLER	= "";
	const FOLDER_APPLICATION		= "";
	const FOLDER_REQUEST_CONTROLER	= "";
	
	/**
	 * @var string caso não seja setada locale, essa é a locale padrão
	 */
	const DEFAULT_LOCALE			= "en_US";
	const DEFAULT_LOCALE_FOLDER_NAME= "";  //  inside language folder
	const LANGUAGE_FOLDER 			= "languages";
	const MO_FILES_FOLDER 			= "translations";
	public static $USE_TRANSLATION	= TRUE;
	const TRANSLATION_ENCODE		= "UTF-8";
	const TRANSLATION_CATEGORY		= LC_ALL;
	const TRANSLATION_CATEGORY_FOLDER_NAME		= "LC_MESSAGES";
	const BAR	=	"/";
	protected static $SELECTED_LOCALE;
	/**
	 * @var int valor em segundos em que uma sessão de usuário logado expira
	 */
	const TIME_LOGIN				= 3000;
	
	static $FOLDER_USER_INFO 		= "";
	/**
	 * pasta root do padrão de rota
	 * 
	 * @var string
	 */
	const FOLDER_ROUTES            = "";
	
	
	public static $sessionClass = array();
	public static $LAST_URL = "";
	public static $DB_LINK;	// para conexao com o banco
	

	private static $array_modules_info;
	private static $array_locale_info = array();
	private static $array_translation_domain = array();
	private static $RewriteVO;
	
	
	public static function addSessionClass($classPath){
        self::$sessionClass[] = $classPath;
    }
    
    public static function includeSessionClass(){
        foreach (self::$sessionClass as $class){
            include_once($class);
        }
    }
	
	public static function setLastUrl($v){
		Session::start();
		$_SESSION["last_url"] = base64_encode($v);
	}
	public static function getLastUrl(){
		Session::start();
		self::$LAST_URL = base64_decode(Navigation::session("last_url"));
		return self::$LAST_URL;
	}
	
	public static $NEXT_URL = "";
	public static function setNextUrl($v){
		Session::start();
		$_SESSION["next_url"] = base64_encode($v);
		self::$NEXT_URL = Navigation::session("next_url");
	}
	public static function getNextUrl(){
		Session::start();
		self::$NEXT_URL = base64_decode(Navigation::session("next_url"));
		return self::$NEXT_URL;
	}
	

	public static function getUrlSite(){
		return $_SERVER["HTTP_HOST"];
	}

	public static $URL_ROOT_APPLICATION		= "";
	public static function setRootApplication($v){
		self::$URL_ROOT_APPLICATION = $v;
	}
	public static function getRootApplication(){
		return self::$URL_ROOT_APPLICATION;
	}
	public static $URL_ALIAS_FOLDER		= "";
	public static function setAliasFolder($f){
		self::$URL_ALIAS_FOLDER = $f;
	}
	public static function getAliasFolder(){
		return self::$URL_ALIAS_FOLDER;
	}
	public static $URL_ROOT_PATH		= "";
	public static function setRootPath($v){
		self::$URL_ROOT_PATH = $v;//str_replace(array("http://"), "",$v);
	}
    /**
     * @param $relative_url
     * @return string
     */
    public static function getRootPath($relative_url = ''){
		return "http://".DataHandler::removeDobleBars(Navigation::getURIDomain()."/" . self::getAliasFolder()."/". $relative_url);
	}
	
	public static $FOLDER_VIEW			= "";
	public static function setFolderView($folder){
		self::$FOLDER_VIEW = $folder;
	}
	public static function getFolderView($path = ''){
		return self::$FOLDER_VIEW . $path;
	}
	
	
	public static function getAsset($relative_url){
		return "http://".DataHandler::removeDobleBars(Navigation::getURIDomain()."/" . self::getAliasFolder() . "/" .self::$FOLDER_VIEW."/".$relative_url);
	}
	public static function getImagePath($relative_url){
		return  self::getRootPath(self::getFolderView("/assets/img/".$relative_url)) ;
	}
	
	
	/**
	 * @return UserInfo
	 */
	public static function getUser(){
		
	}
	public static function setUserFolder($n){
		self::$FOLDER_USER_INFO = $n;
	}
	public static function getUserFolder(){
		return self::$FOLDER_USER_INFO;
	}
	public static function addModuleInfo($module_path, $class_name, $unique_table){
		if(!self::$array_modules_info){
			self::$array_modules_info = array();
		}	
		foreach(self::$array_modules_info as $modules_info){
			if($modules_info->unique_table == $unique_table){
				echo "Erro addModuleInfo Tabela Duplicada!!!";
				return FALSE;
			}
		}
		self::$array_modules_info[] = new ModuleInfoVO($module_path, $class_name, $unique_table);
	}
	
	public static function getModuleInfo($unique_table){
		// echo $unique_table;exit();
		foreach(self::$array_modules_info as $modules_info){
			// echo $unique_table."-".$modules_info->unique_table;
			if($modules_info->unique_table == $unique_table){
				// print_r($modules_info);
				return $modules_info;
			}
		}
		return false;
	}
	
	/*
	 * add a domain to be loaded in all cases
	 */
	public static function addTranslationDomain($domain){
		self::$array_translation_domain[] = $domain;		
	}
	/*
	 * @return all generic domains
	 * */
	public static function getTranslationDomains(){
		return self::$array_translation_domain;
	}
	
	/**
	 * @param $locale
	 * @param $name
	 * @return false em caso de erro
	 */
	public static function addLocaleInfo($locale, $name){
		if(!self::$array_locale_info){
			self::$array_locale_info = array();
		}	
		foreach(self::$array_locale_info as $locale_info){
			if($locale_info->locale == $locale){
				echo "Erro addLocaleInfo Locale Duplicado!!!";
				return FALSE;
			}
		}
		self::$array_locale_info[] = new LocaleInfoVO($locale, $name);
	}
	/**
	 * retorna a array de LocaleInfoVO
	 * @return array or NULL
	 */
	public static function getArrayLocales(){
		return self::$array_locale_info;
	}
	public static function setLocale($locale){
		if(self::existLocaleInfo($locale)){
			self::$SELECTED_LOCALE = trim($locale);
			return TRUE;
		}
		return FALSE;	
	}
	/**
	 * @desc primeiro procura o locale setado
	 * @return string Locale ou NULL
	 */
	static function getLocale(){
		if(self::$SELECTED_LOCALE){
			return self::$SELECTED_LOCALE;
		}else{
			return NULL;//Config::DEFAULT_LOCALE;
		}
	}
	
	/**
	 * @param $locale
	 * @return boolean
	 * @desc verifica se existe o locale passado
	 */
	public static function existLocaleInfo($locale = NULL){
		foreach(self::$array_locale_info as $locale_info){
			if($locale_info->locale == $locale){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * @param $value_to_find
	 * @param $value_to_rewrite
	 * @param $description
	 * @desc adiciona configuração a uma regra $RewriteRuleVO e posteriormente adiciona esse obj
	 * no arrey de regras da Config::RewriteVO
	 **/
	public static function addRewriteRule($value_to_find, $value_to_rewrite, $description){
		if(!self::$RewriteVO){
			self::$RewriteVO = new RewriteVO();
		}
		$RewriteRuleVO = new RewriteRuleVO($value_to_find, $value_to_rewrite, $description);
		self::$RewriteVO->addRewriteRuleVO($RewriteRuleVO);
	}
	
	/**
	 * @param $string
	 * @return $ReturnResultVO
	 * @desc reescreve caso encontrado uma regra a string passada, se não retorna a string.
	 */
	public static function rewriteUrl($string = NULL){
		if(!self::$RewriteVO){
			self::$RewriteVO = new RewriteVO();
		}
		include_once "library/facil3/core/vo/ReturnResultVO.class.php";	
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success  = FALSE;
		//reescreve a string caso haja alguma regra
		$ReturnResultVO = self::$RewriteVO->rewrite($string);
		return $ReturnResultVO;
	}
	
	/**
	 * @return resource a MySQL link identifier on success, or display error on failure.
	 */
	public static function getConection(){
		//if(!self::$DB_LINK){
			try{
				if(!self::$DB_LINK){
					//echo Debug::li(" {".Config::DATA_BASE_SERVER."}, {".Config::DATA_BASE_USER."}, {".Config::DATA_BASE_PASSWORD."} ");
					self::$DB_LINK = mysql_connect(Config::DATA_BASE_SERVER, Config::DATA_BASE_USER, Config::DATA_BASE_PASSWORD);
					if(!mysql_errno()){
						$temp_connect = mysql_select_db(Config::DATA_BASE_NAME , self::$DB_LINK);
						if(mysql_errno()){
							echo "erro ao selecionar database:".mysql_error();
							exit();
						}
					}
				}
				return self::$DB_LINK;
			} catch (Exception $e){
				$ReturnResultVO->sucess  = FALSE;
				$ReturnResultVO->addMessage($e);
				echo "erro de conexao com o banco";
				exit();
			} // end try{
		//}
	}// end public static function getConection(){
	
	
}
