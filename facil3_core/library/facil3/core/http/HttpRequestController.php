<?php
include_once("library/facil3/core/translation/Translation.class.php");
include_once("library/facil3/utils/HtmlHeader.class.php");
include_once("library/facil3/utils/Navigation.class.php");
include_once("library/facil3/utils/Debug.class.php");
include_once("library/facil3/core/acl/UserClient.php");
include_once("library/facil3/utils/ClassHandler.class.php");
include_once "library/facil3/core/http/ReturnSearchClassVO.class.php";

class HttpRequestController {
	private $config;
	static 	$UserClient;
	private $view;
	public  $HttpResult;
	const URI_RETURN_TYPE_STRING	= "URI_RETURN_TYPE_STRING";
	const URI_RETURN_TYPE_ARRAY		= "URI_RETURN_TYPE_ARRAY";
	public function __construct(){
		//iniciando a config básico, a menos que exita outro config, este vai ser o que vai ficar valendo
		include "configs/".str_replace(":8888", "", Navigation::getURIDomain())."/config.php";
		//se não foi passado algum locale na url apos o nome do site faz um redirect passando o locale padrão
		//echo Debug::li(Config::getAliasFolder());
		$folders_array = Navigation::getURI(Config::getAliasFolder(), Navigation::URI_RETURN_TYPE_ARRAY);
		
		
		//echo Debug::li(Config::getRootApplication());
		//$folders_array = Navigation::getURI(Config::getAliasFolder(), Navigation::URI_RETURN_TYPE_ARRAY);
		
		$locale = Config::getLocale();
		
		Translation::setLocale($locale);
		
		//inicia e pega resultado da controller
		$this->HttpResult = $this->getControllerResult();
		
		
		//agora verifica que tipo de retorno esperado e chama a view, se for o caso		
		$ignore_name = Config::getAliasFolder();
		if(Config::getLocale()){
			$ignore_name .= "/".Config::getLocale();
		}
		//url com trata com regras de rota
		$url = explode("/", Config::rewriteUrl(Navigation::getURI($ignore_name, Navigation::URI_RETURN_TYPE_STRING)));
		$retornoDaView = self::searchFile($url, Config::getFolderView());
		//Navigation::searchFileOrFolder(Config::getRootApplication()."/".Config::getLocale(), Config::getFolderView(), Navigation::SEARCH_FILE_MODE_FILE);
		
		//echo Debug::li("retornoDaView");
		//Debug::print_r($retornoDaView);
		
		if($retornoDaView->success){
			//echo Debug::li("retornoDaView:".$retornoDaView->urlToInclude);
			$this->view = $retornoDaView->urlToInclude;
		} else {
			$this->view = Config::getFolderView()."/index.php";
		}
		//exit();
		//Debug::print_r("[SEARCH_FILE_MODE_CLASS_AND_METHOD]:".Navigation::searchFileOrFolder(Config::FOLDER_ROOT_APPLICATION, "view/democrart/", Navigation::SEARCH_FILE_MODE_CLASS_AND_METHOD, "", TRUE));
	}
	/**
	 * inicia a controller conforme configurado em navigation e retorna o resultado do metodo chamado
	 * @return HttpResultVO
	 */
	private function getControllerResult(){
		if(1 == 2){
			$retornoDaController = new ReturnSearchClassVO();
		}
		$ignore_name = Config::getAliasFolder();
		//echo $ignore_name;exit();
		if(Config::getLocale()){
			$ignore_name .= "/".Config::getLocale();
		}
		//url com trata com regras de rota
		$url = explode("/", Config::rewriteUrl(Navigation::getURI($ignore_name, Navigation::URI_RETURN_TYPE_STRING)));
		//inicia a controller
		//echo Config::getRootApplication()."/".Navigation::getLocale();
		$retornoDaController = self::searchController($url, Config::FOLDER_REQUEST_CONTROLER);
		
		
		
		//afiliados ( afiliate) reconhecer aqui a poss�vel origem do internauta atrav�s do restFolder afiliate.N
		$arrayVariable   = Navigation::getVariableArraySlug($retornoDaController->arrayRestFolder);
		
		//echo Debug::li("retornoDaController");
		//Debug::print_r($retornoDaController);
		if(!$retornoDaController->success){
			//pega o nome da classe para instanciar e executa o init
			$tempExplode = explode("/", Config::URL_DEFAULT_CONTROLLER);
			$className = $tempExplode[count($tempExplode)-1];
			$className = str_replace(array(".class.php", ".php"), "", $className);
			
			$retornoDaController->className = $className;
			$retornoDaController->methodName = "init";
			$retornoDaController->urlToInclude = Config::URL_DEFAULT_CONTROLLER;			
		}
		
		//Debug::print_r($retornoDaController);
		
		$className = $retornoDaController->className;
		$methodName = $retornoDaController->methodName;
		//inclui a controller
		include_once($retornoDaController->urlToInclude);
		//instancia
		$instancia = new $className($retornoDaController->arrayRestFolder);
		//executa o metodo e este deve retornar sempre uma HttpResultVO
		if(!ClassHandler::isMethodPublic($instancia, $methodName)){
			$methodName = "init";
		}
		//echo " $className  -> $methodName";
		//exit();
		$HttpResultVO = $instancia->$methodName();
		
		return $HttpResultVO;
	}

	/**
	 * retorna info o user client que está fazendo a requisição
	 * @return UserClient
	 */
	public static function getUserClient(){
		if(HttpRequestController::$UserClient){
			return HttpRequestController::$UserClient;
		} else {
			HttpRequestController::setUserClient(new UserClient());
		}
	}
	/**
	 * @param UserClient $_UserClient
	 * @return unknown_type
	 */
	public static function setUserClient(UserClient $_UserClient){
		HttpRequestController::$UserClient = $_UserClient;
	}
	public static function destroyUserClient(){
		unset(HttpRequestController::$UserClient);
	}
	function getResult(){
		$HttpResult = $this->HttpResult;
		//echo Debug::li("incluindo a view:".$this->view);
		include $this->view;
		return "";
	}

	/**
	 * @param $array_url tem que ser passado o retorno do Navigation::getURI()
	 * @return ReturnSearchClassVO
	 * @desc metodo para buscar controller baseado na url passada
	 */
	private static function searchController($array_url, $_startFolder = ""){
		//iniciando o objeto de retorno
		$returnReturnSearchClassVO 	= new ReturnSearchClassVO();
		$searchFileOrFolderName 	= "";
		//array completa sem a parte filtrada da url
		//echo $search_file_mode;
		//echo $_url;
		$array = $array_url;
		//echo Debug::li("iniciando a busca, array recebida:");
		//Debug::print_r($array);
		$fullStringPath = implode("/", $array);
		$contador = 0;
		//varredura de hierarquia invertida
		for($i = count($array)-1; $i >= 0; $i--){
			//echo "<br /><br />nova linha  $i <br><hr /><br />";
			//precisa tirar um item em que o indice máximo é o $i + 1
			$stringPath 	= //implode(Config::BAR,  array_slice($array, 0 , $i+1));//
								self::getParcialFolderPath($fullStringPath, self::URI_RETURN_TYPE_STRING, $i+1);
			//echo Debug::li("*1 stringPath = ".$stringPath."", FALSE, "FF8800");		
			
			$stringPath		= DataHandler::removeLastBar($stringPath);
			//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF00");		
			
			$lastFolder = "";
			if(($i+1) < count($array)){
				$lastFolder	= "/".DataHandler::removeSpecialCharacters($array[$i+1]);
			}
			//echo Debug::li("*2 lastFolder: ".$lastFolder."", FALSE, "FF4400");		
			
			$currentFolder = "";
			if(($i) < count($array)){
				$currentFolder	= DataHandler::removeSpecialCharacters($array[$i]);
			}
			//echo Debug::li("*3 currentFolder: ".$currentFolder."  ", FALSE, "FF1100");		
			
			//criando a array (além dos limites) - que não foi tratada
			$contador++;
			//echo Debug::li($contador." = valor de contador ", FALSE, "FFFF00");		
			
			$arrayRestFolder = self::getParcialFolderPath($fullStringPath, self::URI_RETURN_TYPE_ARRAY, count($array), count($array)-$contador);
			//$arrayRestFolder = array_slice($array, count($array)-$contador , count($array));
			//echo Debug::li("*4 arrayRestFolder: ".Debug::print_r($arrayRestFolder, true)."  ", FALSE, "AA0000");
			//echo " valor de arrayRestFolder ";
			//echo Debug::print_r($arrayRestFolder);		
			
 			$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
			
	
			$arrayRestFolder = //array_slice($array, $i+1 , count($array));
			
			self::getParcialFolderPath($fullStringPath, self::URI_RETURN_TYPE_ARRAY, FALSE, $i+1);
			//echo " valor de arrayRestFolder ";
			//echo Debug::print_r($arrayRestFolder);
			
			$stringPath 	= "";
			if($i > 0){
				$stringPath = self::getParcialFolderPath($fullStringPath, self::URI_RETURN_TYPE_STRING, $i-1);
			}
//			echo Debug::li(" stringPath depois do if do mal: ".$stringPath, FALSE, "FFFF55");		
			
			$currentFolder = "";
			if(($i) < count($array) ){
				$currentFolder	= DataHandler::removeSpecialCharacters($array[$i]);
			}
			//echo Debug::li($currentFolder." = valor de currentFolder ", FALSE, "FFFF55");		
			
			$currentFolder = str_replace("/", "", $currentFolder);
//			echo Debug::li($currentFolder." =>>> valor de currentFolder ", FALSE, "FFFF55");		

			//procurando folder
			$searchFileOrFolderName = DataHandler::urlFolderNameToClassName($currentFolder);
			//echo " valor de searchFileOrFolderName ";
			//echo Debug::print_r($searchFileOrFolderName);
			//echo "<br>";

			$tempMetodo	= "init";
			if(($i+1) < count($array)){
				$tempMetodo	= DataHandler::urlFolderNameToMethodName($array[$i+1]);
			}
			
//			echo Debug::li("<b>_startFolder:".$_startFolder."</b>", false, NULL, NULL, "00FFff");
//			echo Debug::li("<b>stringPath:".$stringPath."</b>", false, NULL, NULL, "CC0000");
//			echo Debug::li("<b>searchFileOrFolderName:".$searchFileOrFolderName."</b>", false, NULL, NULL, "CC0CC0");
//			echo Debug::li("<b>tempMetodo:".$tempMetodo."</b>", false, NULL, NULL, "0000ff");
//			echo $_startFolder;
			//echo "<br>";
//			var_dump($stringPath);
			//echo $stringPath."/".$searchFileOrFolderName.".php";
			$folderController = DataHandler::removeDobleBars($_startFolder."/".$stringPath."/".$searchFileOrFolderName.".php");
			//echo Debug::li("<b>procurando no if:".$folderController."</b>", false, NULL, NULL, "0000ff");
			if(file_exists($folderController)){
			    
				//echo Debug::li("<b>A classe:".$stringPath."/".$searchFileOrFolderName.".php </b>");
				$returnReturnSearchClassVO->success 		= TRUE;
				$returnReturnSearchClassVO->file 			= $searchFileOrFolderName.".php";
				$returnReturnSearchClassVO->folder			= DataHandler::removeDobleBars($_startFolder."/".$stringPath."/");
				$returnReturnSearchClassVO->urlToInclude	= $folderController;
				$returnReturnSearchClassVO->className		= $searchFileOrFolderName;
				$returnReturnSearchClassVO->methodName		= $tempMetodo;
				$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
				$className 									= $returnReturnSearchClassVO->className;
				
				return $returnReturnSearchClassVO;
			}
			
		}// end for($i = count($array)-1; $i >= 0; $i--){
		return $returnReturnSearchClassVO;
	}
	/**
	 * @param $array_url retorno da Navigation::getUri
	 * @param $_startFolder string passe o caminho da view
	 * @return ReturnSearchClassVO
	 */
	private static function searchFile($array_url, $_startFolder = ""){
		//iniciando o objeto de retorno
		$returnReturnSearchClassVO 	= new ReturnSearchClassVO();
		$searchFileOrFolderName 	= "";//($searchFileOrFolderName != "")?"/".DataHandler::removeSpecialCharacters($searchFileOrFolderName):"";
		//array completa sem a parte filtrada da url
		//echo $search_file_mode;
		//echo $_urlToIgnore;
		$array = $array_url;
			//Navigation::getURI($_urlToIgnore, Navigation::URI_RETURN_TYPE_ARRAY);
		//Debug::print_r($array);
				
		$contador = 0;
		//varredura de hierarquia invertida
		for($i = count($array)-1; $i >= 0; $i--){
			//pegando o string path, tirando o ultimo folder, pois estara no lastFolder
			$stringPath 	= implode("/",  array_slice($array, 0 , $i));//Navigation::getURI($_url, Navigation::URI_RETURN_TYPE_STRING, $i+1);
			//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF00");		
			
			$stringPath		= DataHandler::removeLastBar($stringPath);
			//echo Debug::li($stringPath." = valor de stringPath ", FALSE, "FFFF00");		
			
			//echo Debug::li("if: ".($i+1)." < ".count($array));
			$lastFolder	= DataHandler::removeSpecialCharacters($array[$i]);
			//echo Debug::li($lastFolder." = valor de lastFolder AAAAAAAAAAA ", FALSE, "FFFF00");		
			
			//criando a array (além dos limites) - que não foi tratada
			$contador++;
			//echo Debug::li($contador." = valor de contador ", FALSE, "FFFF00");		
			
			//$arrayRestFolder = Navigation::getURI($_url, Navigation::URI_RETURN_TYPE_ARRAY, count($array), count($array)-$contador);
			$arrayRestFolder = array_slice($array, count($array)-$contador , count($array));
			//echo " valor de arrayRestFolder ";
			//echo Debug::print_r($arrayRestFolder);		
			
 			$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
					//echo Debug::li("<b>{$i}</b>[11] Navigation::SEARCH_FILE_MODE_FILE : ".$_startFolder."----".$stringPath." ");
					$searchFileOrFolderName = "";
					//verifica se tem CAMINHO/ultimaPasta.php
					
					$caminhoBase = $_startFolder."/".trim($stringPath)."/".$lastFolder;
					//echo Debug::li(" antes caminhoBase:".$caminhoBase);
					
					$caminhoBase = DataHandler::removeDobleBars(DataHandler::removeDobleBars($caminhoBase));
					//echo Debug::li(" depois caminhoBase:".$caminhoBase);
					if(file_exists($caminhoBase.".php")){
						$returnReturnSearchClassVO->success 	= TRUE;
						$returnReturnSearchClassVO->className 	= "";
						$returnReturnSearchClassVO->folder 		= "";
						$returnReturnSearchClassVO->urlToInclude	= $caminhoBase.".php";
						$returnReturnSearchClassVO->file			= $lastFolder.".php";
						$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
						return $returnReturnSearchClassVO;
					}
					//verifica se existe CAMINHO/ultimaPasta/index.php
					//echo Debug::li("<b>AAA{$i}</b>[$stringPath] Navigation::SEARCH_FILE_MODE_FILE : ".$_startFolder.$stringPath."/index.php"." ");
					if(file_exists($caminhoBase."/index.php")){
						$returnReturnSearchClassVO->success 	= TRUE;
						$returnReturnSearchClassVO->className 	= "";
						$returnReturnSearchClassVO->folder 		= "";
						$returnReturnSearchClassVO->urlToInclude	= $caminhoBase."/index.php";
						$returnReturnSearchClassVO->file			= "index.php";
                        $returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;
						//echo Debug::li("<b>{$i}</b>[11] Navigation::SEARCH_FILE_MODE_FILE : ". $_startFolder.$stringPath."/index.php");
						return $returnReturnSearchClassVO;
					}
		}// end for($i = count($array)-1; $i >= 0; $i--){
		return $returnReturnSearchClassVO;
	}
	public static function getParcialFolderPath($fullFolderUrl, $ReturnType = HttpRequestController::URI_RETURN_TYPE_ARRAY, $maxRange = FALSE, $initRange = 0){
		$url =  $fullFolderUrl;
		//tirando o nome do site só do início
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
			//tirando os indices menors que o init
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
			case HttpRequestController::URI_RETURN_TYPE_STRING:
				$url = implode("/", $tempArrayFiltrada);
				return $url;
				break;
			case HttpRequestController::URI_RETURN_TYPE_ARRAY:
			default:
				return $tempArrayFiltrada;
				break;
		}
		return $url;
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
