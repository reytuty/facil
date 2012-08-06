<?php
include_once("library/facil3/core/acl/interface/UserInfo.php");
include_once("library/facil3/utils/Session.class.php");

class UserClient implements UserInfo{
	const SESSION_VAR_ID 			= "SESSION_VAR_CLIENT_ID";
	const SESSION_VAR_TYPE_ID 		= "SESSION_VAR_CLIENT_TYPE_ID";
	const SESSION_VAR_NAME 			= "SESSION_VAR_CLIENT_NAME";
	const SESSION_VAR_ACTIVE_TIME 	= "SESSION_VAR_CLIENT_ACTIVE_TIME";
	const SESSION_VAR_ACTIVE 		= "SESSION_VAR_CLIENT_ACTIVE";
	const SESSION_VAR_TOKEN 		= "SESSION_VAR_CLIENT_TOKEN";
	const SESSION_VAR_HISTORY_URI	= "SESSION_VAR_HISTORY_URI";
	
	
    public static function toString(){
    	$temp = "";
    	$temp .= "getActive ".self::getActive()."<br>";
    	$temp .= "getActiveTime ".self::getActiveTime()."<br>";
    	$temp .= "getId ".self::getId()."<br>";
    	$temp .= "getName ".self::getName()."<br>";
    	$temp .= "getToken ".self::getToken()."<br>";
    	$temp .= "getTypeId ".self::getTypeId()."<br>";
    	echo $temp;
    }
	public static function saveUri(){
		Session::start();
		if(!isset($_SESSION[UserClient::SESSION_VAR_HISTORY_URI])){
			$_SESSION[UserClient::SESSION_VAR_HISTORY_URI] = array();
		}
		if(count($_SESSION[UserClient::SESSION_VAR_HISTORY_URI])>3){
			array_shift($_SESSION[UserClient::SESSION_VAR_HISTORY_URI]);
		}
		$_SESSION[UserClient::SESSION_VAR_HISTORY_URI][] = Navigation::getURI(NULL, Navigation::URI_RETURN_TYPE_STRING);
	}
	public static function getUri($index_history = NULL){
		if($index_history == NULL){
			$index_history = count($_SESSION[UserClient::SESSION_VAR_HISTORY_URI])-1;
			if($index_history < 0){
				$index_history = 0;
			}
		}
		return $_SESSION[UserClient::SESSION_VAR_HISTORY_URI][$index_history];
	}
	
	//SETS
	public static function setTypeId($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_TYPE_ID] = $value;
	}
	public static function setId($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_ID] = $value;
	}
	public static function setName($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_NAME] = $value;
	}
	public static function setToken($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_TOKEN] = $value;
	}
	public static function setActiveTime($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME] = $value;
	}
	public static function setActive($value){
	    Session::start();
		$_SESSION[UserClient::SESSION_VAR_ACTIVE] = $value;
	}
	//GET
	public static function getTypeId(){
		if(isset($_SESSION[UserClient::SESSION_VAR_TYPE_ID]) && $_SESSION[UserClient::SESSION_VAR_TYPE_ID]){
			return $_SESSION[UserClient::SESSION_VAR_TYPE_ID];
		}
		return 0;
	}
	public static function getId(){
		return isset($_SESSION[UserClient::SESSION_VAR_ID]) && $_SESSION[UserClient::SESSION_VAR_ID] ? $_SESSION[UserClient::SESSION_VAR_ID] : FALSE  ;
	}
	public static function getName(){
		return isset($_SESSION[UserClient::SESSION_VAR_NAME]) && $_SESSION[UserClient::SESSION_VAR_NAME] ? $_SESSION[UserClient::SESSION_VAR_NAME] : FALSE  ;
	}
	public static function getToken(){
		return isset($_SESSION[UserClient::SESSION_VAR_TOKEN]) && $_SESSION[UserClient::SESSION_VAR_TOKEN] ? $_SESSION[UserClient::SESSION_VAR_TOKEN] : FALSE  ;
	}
	public static function getActiveTime(){
		return isset($_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME]) && $_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME] ? $_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME] : FALSE  ;
	}
	public static function getActive(){
		return isset($_SESSION[UserClient::SESSION_VAR_ACTIVE]) && $_SESSION[UserClient::SESSION_VAR_ACTIVE]  ? $_SESSION[UserClient::SESSION_VAR_ACTIVE] : FALSE  ;;
	}

	public static function kill(){
		self::setActive(NULL);
		self::setActiveTime(NULL);
		self::setId(NULL);
		self::setName(NULL);
		self::setToken(NULL);
		self::setTypeId(NULL);
	} 
	/**
	 * renova a sessão
	 * @return void
	 */
	public static function keepAlive(){
		self::setActiveTime(time());
	}
	/**
	 * verifica se esta logado
	 * @return TRUE or FALSE
	 */
	public static function isAlive(){
		//se estiver logado retorna true e atualiza o active time
		if(isset($_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME]) && $_SESSION[UserClient::SESSION_VAR_ACTIVE_TIME]+Config::TIME_LOGIN > time()){
			self::keepAlive();
			return true;
		}else{
			return false;
		}
	}
}