<?php
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: 	Agora a classe � est�tica
	 * 					Modo de uso:
	 					//de qualquer lugar do c�digo em que a classe j� tenha sido importada:
	 					$variavel = Navigation::get("teste");
	 */
include_once("library/facil3/utils/Validation.class.php");
include_once("library/facil3/utils/DataHandler.class.php");
include_once "library/facil3/core/http/ReturnSearchClassVO.class.php";
class Navigation{
	const URI_RETURN_TYPE_STRING	= "URI_RETURN_TYPE_STRING";
	const URI_RETURN_TYPE_ARRAY		= "URI_RETURN_TYPE_ARRAY";
	
	const SEARCH_FILE_MODE_CLASS_AND_METHOD		= "SEARCH_FILE_MODE_CLASS_AND_METHOD";
	const SEARCH_FILE_MODE_FILE					= "SEARCH_FILE_MODE_FILE";
	const SEARCH_FILE_MODE_FOLDER				= "SEARCH_FILE_MODE_FOLDER";
	/**
	 * para guardar informações sobre a regra de parseamento entre nome de url e nomenclatura de Classe
	 * utilize getArrayRenameRules()
	 * @var array
	 */
	private static $arrayRenameRules;
	static function get($variable){
		//se tiver vazio ou nao estivar setado, retorna null
		return (isset($_GET[$variable]) && $_GET[$variable] != "")?$_GET[$variable]:NULL;
	}
	static function post($variable){
		return (isset($_POST[$variable]) && $_POST[$variable] != "")?$_POST[$variable]:NULL;
	}
	static function session($variable){
		return (isset($_SESSION) && isset($_SESSION[$variable]))?$_SESSION[$variable]:NULL;
	}
	static function sessionStart(){
		//a vantagem de usar esse metodo � que s� inicia se j� n�o tiver iniciado
		if(!isset($_SESSION)){
			session_start();
		}
	}
	static function getVar($variable){
		//verifica primeiro se n�o mandou por post, se n�o tiver mandado, ve por GET
		if(Navigation::post($variable) != NULL){
			return Navigation::post($variable);
		} else if(Navigation::get($variable) != NULL){
			return Navigation::get($variable);
		} else {
			return NULL;
		}
	}
	/**
	 * retorna uma array relacional para troca de caracteres e formação de nome entre url e Classes
	 * @return array
	 */
	static function getArrayRenameRules(){
		if(!self::$arrayRenameRules){
			$arrayChanges = array();
			$arrayChanges["_a"] = "A";
			$arrayChanges["_b"] = "B";
			$arrayChanges["_c"] = "C";
			$arrayChanges["_d"] = "D";
			$arrayChanges["_e"] = "E";
			$arrayChanges["_f"] = "F";
			$arrayChanges["_g"] = "G";
			$arrayChanges["_h"] = "H";
			$arrayChanges["_i"] = "I";
			$arrayChanges["_j"] = "K";
			$arrayChanges["_k"] = "L";
			$arrayChanges["_l"] = "L";
			$arrayChanges["_m"] = "M";
			$arrayChanges["_n"] = "N";
			$arrayChanges["_o"] = "O";
			$arrayChanges["_p"] = "P";
			$arrayChanges["_q"] = "Q";
			$arrayChanges["_r"] = "R";
			$arrayChanges["_s"] = "S";
			$arrayChanges["_t"] = "T";
			$arrayChanges["_u"] = "U";
			$arrayChanges["_v"] = "V";
			$arrayChanges["_x"] = "X";
			$arrayChanges["_y"] = "Y";
			$arrayChanges["_w"] = "W";
			$arrayChanges["_z"] = "Z";
			$arrayChanges["_1"] = "1";
			$arrayChanges["_2"] = "2";
			$arrayChanges["_3"] = "3";
			$arrayChanges["_4"] = "4";
			$arrayChanges["_5"] = "5";
			$arrayChanges["_6"] = "6";
			$arrayChanges["_7"] = "7";
			$arrayChanges["_8"] = "8";
			$arrayChanges["_9"] = "9";
			$arrayChanges["_0"] = "0";
			self::$arrayRenameRules = $arrayChanges;
		}
		return self::$arrayRenameRules;
	}
	/**
	 * Retorna a string do nome do dominio
	 * @return string
	 */
	static function getURIDomain(){
		return $_SERVER["HTTP_HOST"];
	}
	/**
	 * @param string 	$siteName
	 * @param string 	$ReturnType
	 * @param string 	$maxRange
	 * @param int 		$initRange
	 * @param bool 		$byVariable // envie o nome da variavel quando for para pegar valor de variavel de navegação
	 * @return array or string
	 */
	static function getURI($siteName = "", $ReturnType = Navigation::URI_RETURN_TYPE_ARRAY, $maxRange = FALSE, $initRange = 0, $byVariable = NULL){
		
		$siteName = str_replace(array("http://www", "http://", "//"), "", $siteName);
		//sa o ultimo caracter for /, tira
		$siteName = DataHandler::removeLastBar($siteName);
		
		$url = "";
		if($byVariable){
			//$url = Navigation::get($byVariable);
			$url = explode("/", Navigation::get($byVariable));
		} else {
			$url = $_SERVER["REQUEST_URI"];
			if($url[0] == "/"){
				$url = substr($url, 1, strlen($url));
			}
			$url = explode("/", $url);
		}
		if(strpos($siteName, "/")){
			//echo "tem barra";exit();
			$siteName = explode("/", $siteName);
		}
		//tirando o nome do site só do início
		
		if(is_array($siteName)){
			for($i = 0; $i < count($siteName); $i++){
				if(isset($url[$i]) && $url[$i] == $siteName[$i]){
					$url[$i] = "";
				}
			}
		} else {
			$url[0] =  str_replace("$siteName", "", $url[0]);
		}
		$url = implode("/", $url);
		//$url = str_replace("$siteName", "", $_SERVER["REQUEST_URI"]);
		$url = str_replace("//", "/", $url);
		
		//transforma a url em array
		$url  = preg_replace("/(^\/)/", '', $url);
		$tempArray = explode("/", $url);	
        
		if($initRange > 0 || $maxRange !== FALSE){
			$tempTotal = 0;
			if($maxRange !== FALSE){
			    //echo Debug::li("maxRange $maxRange : initRange $initRange");
				$tempTotal = $maxRange;
			} else {
			    $tempTotal = count($tempArray);
			}
			$tempTotal = $tempTotal + 1;
			if($tempTotal > count($tempArray)){
			    $tempTotal = count($tempArray);
			}
           
            
          
			$tempArray = array_slice($tempArray, $initRange , $tempTotal);
		}
		
		//filtra a array conforme as regras
		$tempArrayFiltrada = array();
		for($i = 0; $i < count($tempArray); $i++){
			if($tempArray[$i] != ""){
				$tempArrayFiltrada[] = $tempArray[$i];
			}
		}
		unset($tempArray);
		switch($ReturnType){
			case Navigation::URI_RETURN_TYPE_STRING:
				$url = implode("/", $tempArrayFiltrada);
				return $url;
				break;
			case Navigation::URI_RETURN_TYPE_ARRAY:
			default:
				return $tempArrayFiltrada;
				break;
		}
		return $url;
	}
	/**
	 * Retorna apenas 1 resultado dentro do ReturnSearchFileVO, os dados em ReturnSearchFileVO são todos referentes ao mesmo resultado
	 * @param $_startFolder 	string
	 * @param $searchClass 		string
	 * @return ReturnSearchClassVO
	 */
	public static function searchFileOrFolder($_urlToIgnore = "", $_startFolder = "", $search_file_mode = Navigation::SEARCH_FILE_MODE_FILE, $searchFileOrFolderName = "", $autoInclude = FALSE){
		//iniciando o objeto de retorno
		$returnReturnSearchClassVO 	= new ReturnSearchClassVO();
		$searchFileOrFolderName 	= ($searchFileOrFolderName != "")?"/".DataHandler::removeSpecialCharacters($searchFileOrFolderName):"";
		//array completa sem a parte filtrada da url
		$array = Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_ARRAY);
		
		
		
		//Debug::print_r($array);
		
		$contador = 0;
		//varredura de hierarquia invertida 
		for($i = count($array)-1; $i >= 0; $i--){
			//echo Debug::li($i." = valor de i ", FALSE, "FFFF00");
			
			switch($search_file_mode){
				case Navigation::SEARCH_FILE_MODE_CLASS_AND_METHOD:
                    $arrayRestFolder = Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_ARRAY, FALSE, $i+1);
					//echo " valor de arrayRestFolder ";
					//echo Debug::print_r($arrayRestFolder);		

					$stringPath 	= "";
					if($i > 0){
						$stringPath 	= Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_STRING, $i-1);
					}
					//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF55");		
					
					$currentFolder = "";
					if(($i) < count($array) ){
						$currentFolder	= DataHandler::removeSpecialCharacters($array[$i]);
					}
					//echo Debug::li($currentFolder." = valor de currentFolder ", FALSE, "FFFF55");		
					
					$currentFolder = str_replace("/", "", $currentFolder);
					//echo Debug::li($currentFolder." = valor de currentFolder ", FALSE, "FFFF55");		

					//procurando folder
					$searchFileOrFolderName = DataHandler::urlFolderNameToClassName($currentFolder);
					//echo " valor de searchFileOrFolderName ";
					//echo Debug::print_r($searchFileOrFolderName);		
					//echo "<br>";

					$tempMetodo	= "init";
					if(($i+1) < count($array)){
						$tempMetodo	= DataHandler::urlFolderNameToMethodName($array[$i+1]);
					}
					
					//echo Debug::li("<b>tempMetodo:".$tempMetodo."</b>", false, NULL, NULL, "0000ff");
					//echo $_startFolder.$stringPath."/".$searchFileOrFolderName.".php";
					if(file_exists($_startFolder.$stringPath."/".$searchFileOrFolderName.".php")){
					    
						//echo Debug::li("<b>A classe:".$_startFolder.$stringPath."/".$searchFileOrFolderName.".php </b>");
						$returnReturnSearchClassVO->success 		= TRUE;
						$returnReturnSearchClassVO->file 			= $searchFileOrFolderName.".php";
						$returnReturnSearchClassVO->folder			= DataHandler::removeDobleBars($_startFolder.$stringPath."/");
						$returnReturnSearchClassVO->urlToInclude	= DataHandler::removeDobleBars($_startFolder.$stringPath."/".$searchFileOrFolderName.".php");
						$returnReturnSearchClassVO->className		= $searchFileOrFolderName;
						$returnReturnSearchClassVO->methodName		= $tempMetodo;
						$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
						$className 									= $returnReturnSearchClassVO->className;
						
						if($autoInclude){
							include_once($_startFolder.$stringPath."/".$searchFileOrFolderName.".php");
							//echo $className;
							$classe = new $className();
							//inicia o metodo como vazio
							$returnReturnSearchClassVO->methodName = "init";
							//Se existir uma pasta acima dessa, ve qual é pois pode ser tentativa de acessar metodo
							if(($i+1) < count($array)){
								//echo Debug::li(" >>>>> ".$array[$i+1]);
								$tempMetodo	= DataHandler::urlFolderNameToMethodName($array[$i+1]);
								//verifica se o metodo existe
								if(ClassHandler::isMethodPublic($classe, $tempMetodo)){
									$returnReturnSearchClassVO->methodName = $tempMetodo;
								}
							}
							$metodo = $returnReturnSearchClassVO->methodName;
							//echo $metodo;
							return $classe->$metodo();
						}
						return $returnReturnSearchClassVO;
					}
					break;
				case Navigation::SEARCH_FILE_MODE_FILE:
				default:
					$stringPath 	= Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_STRING, $i+1);
					//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF00");		
					
					$stringPath		= DataHandler::removeLastBar($stringPath);
					//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF00");		
					
					$lastFolder = "";
					if(($i+1) < count($array)){
						$lastFolder	= "/".DataHandler::removeSpecialCharacters($array[$i+1]);
					}
					//echo Debug::li($lastFolder." = valor de lastFolder ", FALSE, "FFFF00");		
					
					$currentFolder = "";
					if(($i) < count($array)){
						$currentFolder	= DataHandler::removeSpecialCharacters($array[$i]);
					}
					//echo Debug::li($currentFolder." = valor de currentFolder ", FALSE, "FFFF00");		
					
					//criando a array (além dos limites) - que não foi tratada
					$contador++;
					//echo Debug::li($contador." = valor de contador ", FALSE, "FFFF00");		
					
					$arrayRestFolder = Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_ARRAY, count($array), count($array)-$contador);
					//echo " valor de arrayRestFolder ";
					//echo Debug::print_r($arrayRestFolder);		
					
		 			$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
		 			
					$stringPath 	= "";
					if($i+1 >= 1){
						$stringPath 	= Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_STRING, $i);
					}
					
					//echo Debug::li("<b>{$i}</b>[11] Navigation::SEARCH_FILE_MODE_FILE : ".$_startFolder."----".$stringPath." ");
					$searchFileOrFolderName = "";
					if(file_exists($_startFolder.$stringPath.".php")){
						$returnReturnSearchClassVO->success 	= TRUE;
						$returnReturnSearchClassVO->className 	= "";
						$returnReturnSearchClassVO->folder 		= "";
						$returnReturnSearchClassVO->urlToInclude	= $_startFolder.$stringPath.".php";
						$returnReturnSearchClassVO->file			= $lastFolder.".php";
						$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
						return $returnReturnSearchClassVO;
					}
					//echo Debug::li("<b>AAA{$i}</b>[11] Navigation::SEARCH_FILE_MODE_FILE : ".$_startFolder.$stringPath."/index.php"." ");
					if(file_exists($_startFolder.$stringPath."/index.php")){
						$returnReturnSearchClassVO->success 	= TRUE;
						$returnReturnSearchClassVO->className 	= "";
						$returnReturnSearchClassVO->folder 		= "";
						$returnReturnSearchClassVO->urlToInclude	= $_startFolder.$stringPath."/index.php";
						$returnReturnSearchClassVO->file			= "index.php";
                        $returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
						//echo Debug::li("<b>{$i}</b>[11] Navigation::SEARCH_FILE_MODE_FILE : ". $_startFolder.$stringPath."/index.php");
						return $returnReturnSearchClassVO;
					}
					break;
			}//end switch($search_file_mode){
			
		}// end for($i = count($array)-1; $i >= 0; $i--){
		return $returnReturnSearchClassVO;
	}
	/**
	 * Retorna objeto com variaveis com seus respectivos valores e array de slug 
	 * melhorias: se passado duas variaveis iguais de valores diferentes salvar os valores como array em uma unica variavel 
	 * @return string
	 */
	static function getVariableArraySlug($tempArrayRestFolder = NULL, $typeSeparete = "."){
		$arrayVariable = array();
		if(is_array($tempArrayRestFolder) && count($tempArrayRestFolder) > 0){
			foreach($tempArrayRestFolder as $str){
				$explode = explode($typeSeparete, $str);
				//print_r($explode);
				if(count($explode)>1){
					$variable = array_shift($explode);
					$arrayVariable[$variable] = urldecode(implode($typeSeparete, $explode));
				}
				//print_r($arrayVariable);
			}
		}
		//Debug::print_r($arrayVariable);exit();
		return $arrayVariable;
	}
    
    static function redirect($path = ""){
    	//echo "redirecionar para".Config::getRootPath($path);
    	//exit();
        header("Location:" . Config::getRootPath($path));
        exit;
    }
}