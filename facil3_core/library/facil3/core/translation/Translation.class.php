<?php

/*
 * @author 		: 	Alan Lucian Milanês Tormente ( alanlucian@gmail.com)
 * @date		: 	05/12/2011
 * @version		: 	0.1
 * @desc		: 	simply add MO files to use translations. Work on Windows (IIS/Apache) and Unix systems
 * 					
 */

 
 
class Translation{
 	
	private static $locale = '';
	private static $def    = 'default-';
	public static function setLocale($new_locale=''){
		
		if(!is_string($new_locale))
			$new_locale = Config::DEFAULT_LOCALE;
			
		self::$locale = $new_locale;
		self::loadMOFiles();
		self::$def = self::$locale;
	}
	
	public static function text($text, $domain = FALSE, $echo = FALSE ){
		
		if(!$domain || $domain == FALSE || $domain == ''){
			$domain =  "default";//self::$def;
		}
		
		$domain = $domain . '-' .  self::$locale;
		
		
		// var_dump($domain, $text);
		try{
			$rt = $text;
			//$rt = dgettext($domain, $text);
		}catch(Exception $error){
			var_dunp($error);
		}
		//exit();
	
		//$rt = $text;
		if($echo){
			echo $rt;
		}else{
			return $rt;
		}
		
	}
	
	private static function updateMOFiles(){
		
		$def_lang_folder = Config::MO_FILES_FOLDER . Config::BAR . Config::DEFAULT_LOCALE_FOLDER_NAME;  
		
		$def_lang_category_folder = $def_lang_folder . Config::BAR . Config::TRANSLATION_CATEGORY_FOLDER_NAME;
		
		if(!is_dir($def_lang_folder))
			mkdir($def_lang_folder);
			
			
		if(!is_dir($def_lang_category_folder))
			mkdir($def_lang_category_folder);
		
		$domains = Config::getTranslationDomains();	
		foreach($domains as $domain){
			$domain_file = $domain . "-" . self::$locale . ".mo";
			
			$domain_mo_path =  Config::LANGUAGE_FOLDER . Config::BAR . $domain_file ;
			
			if(file_exists($domain_mo_path)){
				copy($domain_mo_path, $def_lang_category_folder . Config::BAR . $domain_file);
			}
		}
		
		
		if(self::$locale != Config::DEFAULT_LOCALE_FOLDER_NAME){
			$lang_folder = Config::MO_FILES_FOLDER . Config::BAR . self::$locale;
			$lang_category_folder = $lang_folder . Config::BAR . Config::TRANSLATION_CATEGORY_FOLDER_NAME;
			
			if(!is_dir($lang_folder))
				mkdir($lang_folder);
				
			
			if(!is_dir($lang_category_folder))
				mkdir($lang_category_folder);
			
			
			$domains = Config::getTranslationDomains();	
			foreach($domains as $domain){
				$domain_file = $domain . "-" . self::$locale . ".mo";
				
				$domain_mo_path =  Config::LANGUAGE_FOLDER . Config::BAR . $domain_file ;
				
				if(file_exists($domain_mo_path)){
					copy($domain_mo_path, $lang_category_folder . Config::BAR . $domain_file);
				}
			}
		}
	} 
	
	private static function loadMOFiles(){
		
		self::updateMOFiles();
		
		$lang_path = Config::LANGUAGE_FOLDER;
		
		$domains = Config::getTranslationDomains();
		
		foreach($domains as $domain){
			
			$domain = $domain . '-' . self::$locale;
			
			bindtextdomain( $domain, Config::MO_FILES_FOLDER );
			bind_textdomain_codeset( $domain, Config::TRANSLATION_ENCODE);
			
		}
		
		//setlocale(Config::TRANSLATION_CATEGORY, self::$locale);
		if(!Config::$USE_TRANSLATION)
			return;//para nao dar erro no mac
		
		// var_dump(self::$locale , $lang_path);
		//bindtextdomain( self::$def , $lang_path );
		//bind_textdomain_codeset( self::$def , Config::TRANSLATION_ENCODE);
		//textdomain(self::$def);
		
	}
	
	
	
	
 }
