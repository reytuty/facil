<?php
 /*
 * @autor		: Renato Miawaki - reytuty@gmail.com
 * @data		: 15/12/2009
 * @versao		: 1.0
 * @comentario	: listaDiretorio($urlDoDiretorio) - metodo adicional para listar arquivos de um diretï¿½rio
 */
 /*
 * @autor		: Mauricio Amorim
 * @data		: 07/07/2010
 * @versao		: 1.1 (inglï¿½s)
 * @comentario	: listDirectory($urlOfDirectory) - metodo adicional para listar arquivos de um diretï¿½rio
 * @description	: 	Classe para auxilio na manipulaï¿½ï¿½o de dados.
 */ 
class DataHandler{
	static $array_relacional_utf8_iso;
	/**
	 * @param 	string $date
	 * @return 	string
	 * @coment 	Envie o data em qualquer formato e esse metodo deve deixar no formatod o banco
	 * 			atualmente aceita dd-mm-aaaa ou aaaa-dd-mm
	 */
	static function convertDateToDB($date){
		if(preg_match_all("/([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9][0-9]{2}) ([0-2]?[0-9]):([0-6]?[0-9]):([0-6]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[3][0]."-".$arrayDate[2][0]."-".$arrayDate[1][0]." ".$arrayDate[4][0].":".$arrayDate[5][0].":".$arrayDate[6][0];
		} else if(preg_match_all("/([0-9]?[0-9][0-9]{2})[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9]) ([0-2]?[0-9]):([0-6]?[0-9]):([0-6]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[1][0]."-".$arrayDate[2][0]."-".$arrayDate[3][0]." ".$arrayDate[4][0].":".$arrayDate[5][0].":".$arrayDate[6][0];
		} else if(preg_match_all("/([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9][0-9]{2})/", $date, $arrayDate)){
			$date = $arrayDate[3][0]."-".$arrayDate[2][0]."-".$arrayDate[1][0];
		} else if(preg_match_all("/([0-9]?[0-9][0-9]{2})[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[1][0]."-".$arrayDate[2][0]."-".$arrayDate[3][0];
		}
		return $date;
	}
	
	public static function convertToEncoding($string, $new_encoding){
		$old_encoding = mb_detect_encoding($string);
		return mb_convert_encoding($string, $old_encoding, $new_encoding);
	}
	public static function objectSort(&$data, $key){
		for ($i = count($data) - 1; $i >= 0; $i--){
		  $swapped = false;
		  for ($j = 0; $j < $i; $j++){
		       if ($data[$j]->$key > $data[$j + 1]->$key){
		            $tmp = $data[$j];
		            $data[$j] = $data[$j + 1];       
		            $data[$j + 1] = $tmp;
		            $swapped = true;
		       }
		  }
		  if (!$swapped) return;
		}
	}
	public static function utf8ToIso($string){
		if(!self::$array_relacional_utf8_iso){
			$utf8_array = array();
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = '"';
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "<";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = ">";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "&";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$utf8_array[] = "�";
			$array_relacional = array();
		
			foreach($utf8_array as $iso_char){
				$array_relacional[utf8_encode($iso_char)] = $iso_char;
			}
			self::$array_relacional_utf8_iso = $array_relacional;
		}
		$new_string = str_replace(array_keys(self::$array_relacional_utf8_iso), array_values(self::$array_relacional_utf8_iso), $string);
		return $new_string;
	}
	
	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function urlFolderNameToClassName($urlFolderName){
		$urlFolderName = strtolower($urlFolderName);
		$arrayChanges = Navigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$urlFolderName = str_replace($key, $value, $urlFolderName);
		}
		$urlFolderName = ucfirst($urlFolderName);
		return $urlFolderName;
	}
	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function urlFolderNameToMethodName($urlFolderName){
		$urlFolderName = strtolower($urlFolderName);
		$arrayChanges = Navigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$urlFolderName = str_replace($key, $value, $urlFolderName);
		}
		return $urlFolderName;
	}
	static function classNameToUrlFolderName($className){
		$arrayChanges = Navigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$className = str_replace($value, $key, $className);
		}
		if(strlen($className) > 0 && $className[0] == "_"){
			$className[0] = "";
		}
		//$className = preg_replace("/^_/", "", $className);
		return $className;
	}
	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function returnFirstAndLastName($name){
		$name = str_replace("  ", " ", $name);
		$array_nomes = explode(" ", $name);
		if(count($array_nomes) > 1){
			$name = $array_nomes[0]." ".$array_nomes[count($array_nomes)-1];
		}
		return $name;
	}
	static function convertDbDateToLocale($locale = "pt-br", $date_time, $noTime = FALSE){
		switch($locale){
			case "en":
				return DataHandler::convertDateToEua($date_time, $noTime);
				break;
			case "pt-br":
			default:
				return DataHandler::convertDateToBrazil($date_time, $noTime);
				break;
		}
	}
	
	/** 
	 * pega o ID do video do youtube
	 * @param $url 
	 * @return $vid
	 */
	public static function getYoutubeVideoId($url){
		if($url === null){ return ""; }
				
		 	 			  
		preg_match("/[\\?&]v=([^&#]*)/", $url, $out);
		
		if(!sizeof($out)>0)
			return '';
		 
		 	
		$vid = $out[1];
		
		return $vid;
	}
	
	/** 
	 * pega imagem do video do youtube
	 * @param $url
	 * @param $size  
	 * @return $vid
	 */
	public static function getYoutubeThumb( $url, $size = 'small')	{
			
		$vid = DataHandler::getYoutubeVideoId($url);
		
		if($size == "small"){
			$rt = "http://img.youtube.com/vi/" . $vid . "/2.jpg";
		}else {
			$rt ="http://img.youtube.com/vi/" . $vid . "/0.jpg";
		}
		return $rt;
	}
	
	
	/** 
	 * atenÃ§Ã£o sÃ³ converte data vindo com formato do banco
	 * @param $date_time
	 * @param $noTime
	 * @return date
	 */
	static function convertDateToEua($date_time, $noTime = FALSE){
		# ($date_time, $output_string, $utilizar_funcao_date = false) {
		// Verifica se a string estÃ¡ num formato vÃ¡lido de data ("aaaa-mm-dd" ou "aaaa-mm-dd hh:mm:ss")
		if (preg_match("/^(\d{4}(-\d{2}){2})( \d{2}(:\d{2}){2})?$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			$valor['y'] = substr($date_time, 2, 2);
			$valor['H'] = substr($date_time, 11, 2);
			$valor['i'] = substr($date_time, 14, 2);
			$valor['s'] = substr($date_time, 17, 2);
			// Verifica se a string estï¿½ num formato vÃ¡lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{2}(:\d{2}){2})?$/", $date_time)) {
			//se nÃ£o tinha hora na data enviada, vai sem data
			$noTime = TRUE;
			$valor['d'] = NULL;
			$valor['m'] = NULL;
			$valor['Y'] = NULL;
			$valor['y'] = NULL;
			$valor['H'] = substr($date_time, 0, 2);
			$valor['i'] = substr($date_time, 3, 2);
			$valor['s'] = substr($date_time, 6, 2);
		} else {
			return NULL;
		}
		if($noTime){
			$return = $valor['m']."-".$valor['d']."-".$valor['Y'];
		}else{
			$return =  $valor['m']."-".$valor['d']."-".$valor['Y']." ".$valor['H'].":".$valor['i'].":".$valor['s'];
		}
		if($return != "--"){
			return $return;
		}
		return NULL;
	}
	
	/**
	 * @param $date_time
	 * @param $noTime
	 * @return string data
	 */
	static function convertDateToBrazil($date_time, $noTime = FALSE){
		if($date_time == ""){
			return "";
		}
		# ($date_time, $output_string, $utilizar_funcao_date = false) {
		// Verifica se a string estï¿½ num formato vï¿½lido de data ("aaaa-mm-dd" ou "aaaa-mm-dd hh:mm:ss")
		if (preg_match("/^(\d{4}(-\d{2})-\d{2})$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			// Verifica se a string estï¿½ num formato vï¿½lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{4}(-\d{2})-\d{2}) (\d{2}(:\d{2}):\d{2})?$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			$valor['y'] = substr($date_time, 2, 2);
			$valor['H'] = substr($date_time, 11, 2);
			$valor['i'] = substr($date_time, 14, 2);
			$valor['s'] = substr($date_time, 17, 2);
			// Verifica se a string estï¿½ num formato vï¿½lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{2}(:\d{2}){2})?$/", $date_time)) {
			//se nÃ£o tinha hora na data enviada, vai sem data
			$noTime = TRUE;
			$valor['d'] = NULL;
			$valor['m'] = NULL;
			$valor['Y'] = NULL;
			$valor['y'] = NULL;
			$valor['H'] = substr($date_time, 0, 2);
			$valor['i'] = substr($date_time, 3, 2);
			$valor['s'] = substr($date_time, 6, 2);
		} else {
			return $date_time;
		}
		if($noTime){
			return $valor['d']."/".$valor['m']."/".$valor['Y'];
		}else{
			return  $valor['d']."/".$valor['m']."/".$valor['Y']." ".$valor['H'].":".$valor['i'].":".$valor['s'];
		}
		
	}
	
	static function returnFilenameWithoutExtension($name){
		$name_array = explode(".", $name);
		$name = "";
		for($i = 0; $i < count($name_array)-1; $i++){
			$name .= $name_array[$i];
		}
		return $name;
	}
	static  function returnExtensionOfFile($name){
		$name_array = explode(".", $name);
		return $name_array[count($name_array)-1];
	}
	static function createRecursiveFoldersIfNotExists($url){
		$array_folders = explode("/", $url);
		if(is_array($array_folders) && count($array_folders) > 0){
			$totalFolder = "";
			foreach($array_folders as $folder){
				$totalFolder .= $folder."/";
				self::createFolderIfNotExist($totalFolder);
			}
		} else {
			self::createFolderIfNotExist($url);
		}
	}
	static function createFolderIfNotExist($url){
		//fazer o upgrade para ser recursivo
		if(!file_exists($url)){
			@mkdir($url);
		}
		@chmod($url, 0777);
	}
	static function convertMoneyToDB($valueString){
		if(strpos($valueString, ',') === FALSE)
            return (float)$valueString;    
		$valueString = str_replace(".", "", $valueString);
		$valueString = str_replace(",", ".", $valueString);
		return (float)$valueString;
	}
	static function convertMoneyToBrazil($valueString, $simbol= TRUE){
		$changeNumberF = true;
	    if(strpos($valueString, ',') !== FALSE)
            $changeNumberF = false;
		
		if($changeNumberF){
			$valueString = number_format((float) $valueString, 2 , ',', '.');
			
		}    
		return  ($simbol && strpos($valueString, 'R$') === FALSE ? 'R$ ' : '') . $valueString ;
		
		$valueString = str_replace(".", ",", $valueString);
		if(!preg_match_all("/.*,(.*)?/", $valueString, $arrayValue)){
			$valueString .= ",00";
		}
		//echo $arrayValor[1]." : ".($arrayValor[1])."<br>";
		if($arrayValue[1] < 10 && $arrayValue[1] > 0){
			$valueString .= "0";
		}
		//print_r($arrayValor[1]);
		return $valueString;
	}
	static function removeDobleBars($string){
	    return str_replace(array("////", "///", "//"), "/", $string);
	}
	/**
	 * @param $string_folder
	 * @return string
	 */
	static function removeLastBar($string_folder){
		//echo Debug::li("string_folder:$string_folder");
		if(strlen($string_folder) > 0 && $string_folder[strlen($string_folder)-1] == "/"){
			$string_folder = substr($string_folder, 0, strlen($string_folder)-1);
		}
		//echo Debug::li("string_folder retornando:$string_folder");
		return $string_folder;
	}
	/**
	 * @param $string_folder
	 * @return string
	 
	static function removeLastBar($string_folder){
		//echo Debug::li("string_folder:$string_folder");
		if(strlen($string_folder) > 0 && $string_folder[strlen($string_folder)-1] == "/"){
			$string_folder = substr($string_folder, 0, strlen($string_folder)-2);
		}
		//echo Debug::li("string_folder retornando:$string_folder");
		return $string_folder;
	}*/
	static function removeEntityAccent($str, $encode = 'UTF-8'){
		$acentos = array(
		        'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
		        'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
		        'C' => '/&Ccedil;/',
		        'c' => '/&ccedil;/',
		        'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
		        'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
		        'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
		        'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
		        'N' => '/&Ntilde;/',
		        'n' => '/&ntilde;/',
		        'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
		        'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
		        'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
		        'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
		        'Y' => '/&Yacute;/',
		        'y' => '/&yacute;|&yuml;/',
		        'a.' => '/&ordf;/',
		        'o.' => '/&ordm;/',
				' ' => '/&nbsp;|&bull;|&ldquo;/'
				
				
		);
		return preg_replace(array_values($acentos), array_keys($acentos), $str);
	}
	static function removeAccent($string, $entitys_to = FALSE){
		//return $string;
		$string = str_replace(explode(" ", "Ã Á À Â"), 'A', $string);
		$string = str_replace(explode(" ", "ã á à â"), 'a', $string);
		
		$string = str_replace(explode(" ", "É È Ê"), 'E', $string);
		$string = str_replace(explode(" ", "é è ê"), 'e', $string);
		
		$string = str_replace(explode(" ", "Í Ì Î "), 'I', $string);
		$string = str_replace(explode(" ", "í ì î"), 'i', $string);
		
		$string = str_replace(explode(" ", "Õ Ó Ò Ô"), 'O', $string);
		$string = str_replace(explode(" ", "õ ó ò ô"), 'o', $string);
		
		$string = str_replace(explode(" ", "Ú Ù Û"), 'U', $string);
		$string = str_replace(explode(" ", "ú ù û"), 'u', $string);
		
		$string = str_replace("ç", 'c', $string);
		$string = str_replace("Ç", 'C', $string);
		
		if($entitys_to){
			$string = self::removeEntityAccent($string);
		}
		return $string;
    }
	static function removeSpecialCharacters($string){
		$string = str_replace("/", "", $string);
		$string = str_replace(".", "", $string);
		return @ereg_replace("[^a-zA-Z0-9_-]", "", $string);
	}
	static function strToURL($string){
		
//		echo $string;
		$string = trim($string);
		$string = str_replace("  ", " ", $string);
		$string = str_replace(" ", "-", $string);
//		DataHandler::
		$string = self::removeAccent($string, TRUE);
//		echo $string;
		$string = mb_strtolower($string, mb_detect_encoding($string));
//		echo $string;
//		echo "</br>";
		return $string;
	}
	static function addQuotes($string){
		$string = str_replace("'", "\'", $string);
		$string = str_replace("\"", "\\\"", $string);
		return $string;
	}
	
	/**
	 * retorna string sem tags, html entitys e sem acento
	 * @param $text (string)
	 * @return string
	 * 
	 */
	static function cleanStringsForSearch($text){
		$text = self::forceString($text, TRUE);
		$text = self::removeAccent($text, TRUE);
		$text = str_replace(array("    ", "   ", "  ", "
"), " ", $text);
		
		return trim($text);
	}
	static function writeFile($place, $name, $content, $fopenParam = "a+"){
		/*
		'r'  	 Abre somente para leitura; coloca o ponteiro do arquivo no comeï¿½o do arquivo.
		'r+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo.
		'w' 	Abre somente para escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo e reduz o comprimento do arquivo para zero. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'w+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo e reduz o comprimento do arquivo para zero. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'a' 	Abre somente para escrita; coloca o ponteiro do arquivo no final do arquivo. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'a+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no final do arquivo. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'x' 	Cria e abre o arquivo somente para escrita; coloca o ponteiro no comeï¿½o do arquivo. Se o arquivo jï¿½ existir, a chamada a fopen() falharï¿½, retornando FALSE e gerando um erro de nï¿½vel E_WARNING. Se o arquivo nï¿½o existir, tenta criï¿½-lo. Isto ï¿½ equivalente a especificar as flags O_EXCL|O_CREAT para a chamada de sistema open(2).
		'x+' 	Cria e abre o arquivo para leitura e escrita; coloca o ponteiro no comeï¿½o do arquivo. Se o arquivo jï¿½ existir, a chamada a fopen() falharï¿½, retornando FALSE e gerando um erro de nï¿½vel E_WARNING. Se o arquivo nï¿½o existir, tenta criï¿½-lo. Isto ï¿½ equivalente a especificar as flags O_EXCL|O_CREAT para a chamada de sistema open(2). 
		*/
		if (!$handle = fopen($place.$name, $fopenParam)) {
			return false;
			exit;
		}
	   if (!fwrite($handle, $content)) {
			return false;
			exit;
	   }
	   fclose($handle);
	   return true;
	}
	public static function deleteDirectory($urlOfDirectory){
		$arrayExeptionFiles = array(".", "..");
		try{
			$result = DataHandler::listDirectory($urlOfDirectory);
			foreach($result as $item){
				$extencion = "";
				if(preg_match("/\./", $item)){
					//tem um ponto de extenção
					$extencion = strtolower(DataHandler::returnExtensionOfFile($item));
				}
				$fileUrl = DataHandler::removeDobleBars($urlOfDirectory."/".$item);
				if($extencion == ""){
						if(in_array($item, $arrayExeptionFiles)){
							//não fazer nada com esse
						} else {
							$newfolder = DataHandler::removeDobleBars($urlOfDirectory."/".$item);
							
							//é um folder, vai varrer dentro do folder tb - recursivo
							if(is_dir($newfolder)){
								//echo Debug::li("a pasta será varrida:".$newfolder);
								self::deleteDirectory($newfolder);
							} else {
								unlink($newfolder);
							}
						}
				} else {
					//ve se é um arquivo a se deletar
					unlink($fileUrl);
				}
			}
			//deleta o próprio diretório enviado
			rmdir($urlOfDirectory);
		} catch(Exception $e){
			//mudinho
			return FALSE;
		}
		
	}
	static function listDirectory($urlOfDirectory, $extention = "*"){
		//$extencao : envie "jpg" caso queira sï¿½ os arquivos .jpg
		$arrayFiles = array();
		$extention = strtolower(str_replace(".", "", $extention));
		if (is_dir($urlOfDirectory)) {
			if ($dh = opendir($urlOfDirectory)) {
				while (($file = readdir($dh)) !== false) {
					if($extention == "*"){
						array_push($arrayFiles, $file);
					} else {
						//filtrar pela extenï¿½ï¿½o
						if(strtolower($this->retornaExtencaoDoArquivo($file)) == $extention){
							//ï¿½ do mesmo tipo procurado
							array_push($arrayFiles, $file);
						}
					}
				}
				closedir($dh);
			}
		} else {
			throw new Exception(str_replace("php[url]", $urlOfDirectory,Translation::text("LibraryLanguage::ERROR_DATA_HANDLER_DIRECTORY_NOT_EXISTS")));
		}
		return $arrayFiles;
	}
	static function forceType($valor, $type = "string"){
		switch($type){
			case "string":
			case "date":
				return DataHandler::forceString($valor);
				break;
			case "number":
				return DataHandler::forceNumber($valor);
				break;
			case "int":
				return DataHandler::forceInt($valor);
				break;
			default:
				return DataHandler::forceString($valor);
				break;
		}
	}
	static function forceString($valor, $stripTag = FALSE, $scape_string = TRUE){
		if($stripTag){
			$valor = strip_tags($valor);
		}
//		$valor = nl2br($valor);
		if($scape_string){
			//troca " por html entitis";
			$valor = str_replace("\"", '&#034;', $valor);
			//troca ' por html entitis;
			$valor = str_replace("'", "&#039;", $valor);
//			$valor = mysql_escape_string($valor);
		}
		return (string) $valor;
	}
	/**
	 * Pode mandar com virgula que transforma em nÃºmero, mantendo os decimais
	 * @param string $valor
	 * @return Number
	 */
	static function forceNumber($valor){
		//verifica se tem virgula
		if(strpos($valor, ",")){
			//tem virgula, entÃ£o ve se Ã© sÃ³ uma
			$temp_array = explode(",", $valor);
			if(count($temp_array) == 2){
				//tem apenas uma virgula, entÃ£o beleza
				$valor = str_replace(".", "", $valor);
				$valor = str_replace(",", ".", $valor);
			}//nÃ£o tem else, se tiver mais de uma virgula, transforma com o *1
		}
		return $valor*1;
	}
	static function bytesInString($bytes){
	    if ($bytes < 1024) {
	        return $bytes .' Bytes';
	    } elseif ($bytes < 1048576) {
	        return round($bytes / 1024, 2) .' Kb';
	    } elseif ($bytes < 1073741824) {
	        return round($bytes / 1048576, 2) . ' Mb';
	    } elseif ($bytes < 1099511627776) {
	        return round($bytes / 1073741824, 2) . ' Gb';
	    } else {
	        return round($bytes / 1099511627776, 2) .' Tb';
	    }
	}
	static function forceInt($valor){;
		return (int) $valor*1;
	}
	static function ecmaToUnderline($string){
		$newString 			= "";
		for($i = 0; $i < strlen($string); $i++){
			//$string[$i]
			
			if(DataHandler::isUpper($string[$i])){
				$newString .= "_".strtolower($string[$i]);
			} else {
				$newString .= $string[$i];
			}
		}
		return $newString;
	}
	/**
	 * @param string $char
	 * @return Boolean
	 */
	static function isUpper($char){
		return ctype_upper($char);
	}
	/**
	 * @param string $char
	 * @return Boolean
	 */
	static function isLower($char){
		return !DataHandler::isUpper($char);
	}
	static function arrayToXML($array){
		if(!is_array($array)){
			return "";
		}
		
	}
	/**
	 * @param object $obj
	 */
	static function objToXML($obj){
		$str_xml = "";
		foreach($obj as $key=>$value){
			//Debug::li("varrendo key [$key] ");
			if(is_numeric($key)){
				$key = "item";
			}
			$str_xml .= "<$key>";
			if(is_array($value)){
				$str_xml .= DataHandler::objToXML($value);
			} else if(is_object($value)){
				$str_xml .= DataHandler::objToXML($value);
			} else {
				if(is_bool($value)){
					$value = ($value == TRUE)?"1":"0";
				}
				$str_xml .= "$value";
			}
			$str_xml .= "</$key>";
		}
		return $str_xml;
	}
	
	
	static function addSimpleFlash($largura, $altura, $nomeSwf, $arrayParametros=array(), $transparent=false, $cor="#000000", $idFlash = NULL){

		//$urlTemplate = "classes/utils/templates/flash/object.html";
		
		if($idFlash == NULL || $idFlash == ""){
			$rand1 = rand(1, 3255);
			$rand2 = rand(1, 3255);
			$rand3 = rand(1, 3255);
			$idFlash = "flash_".$rand1."_".$rand2."_".$rand3;
		}
		###id_div_flash###
		
		global $URL;
		
		$flashConteudoSujo = "<div id=\"$idFlash\"></div><script>
var so = new SWFObject('".$URL.$nomeSwf."', '".$nomeSwf."', '".$largura."', '".$altura."', '9', '".$cor."');
    ###config###
    so.write('".$idFlash."');
	</script>";//file_get_contents($urlTemplate);
		$config = "";
		//criando a array de parametros
		if(count($arrayParametros)>0){
			$parametros = "?";
			for($i = 0; $i < count($arrayParametros); $i++){
				$config .= "
	so.addVariable(\"".$arrayParametros[$i][0]."\", \"".$arrayParametros[$i][1]."\");";
			}
		}
		if($transparent){
			$config .= "
	so.addParam('wmode', 'transparent')";
		}
		$flashConteudoSujo = str_replace("###config###", $config, $flashConteudoSujo);
		//echo "<br> Debug mais que do mal:((((((".$flashConteudoSujo.")))))))))))";
		return $flashConteudoSujo;

	}
	/**
	 * transforma em data formato banco ou NOW() caso venha vazio, se enviar NOW() mantem NOW
	 * @param string $date
	 * @return string
	 */
	public static function dateHandlerNowOrDate($date = NULL){
		return (strtoupper($date) == "NOW()"||strtoupper($date) == "NOW")?"NOW()":self::convertDateToDB($date);
	}
	/**
	 * @param unknown_type $urlToSend
	 * @param unknown_type $titulo
	 * @param unknown_type $rotulo
	 * @param unknown_type $descriptionFile
	 * @param unknown_type $extencoesValidas
	 * @param unknown_type $urlToGet
	 * @param unknown_type $urlToDelete
	 * @param unknown_type $urlToCapa
	 * @param unknown_type $urlToGetThumb
	 * @param unknown_type $funcaoNoTermino
	 * @param unknown_type $jsID
	 * @param unknown_type $limiteDeFotos
	 * @return mixed
	 */
	static function addFlashSendImage(
			$urlToSend, 
			$titulo = "fotos",
			$rotulo = "escolher arquivo", 
			$descriptionFile  = "Todos os Arquivos", 
			$extencoesValidas = "*.jpg; *.jpeg", 
			$urlToGet = NULL, 
			$urlToDelete = NULL, 
			$urlToCapa = NULL,
			$urlToGetThumb = "", 
			$funcaoNoTermino = "alert", $jsID = "envioFoto", $limiteDeFotos = NULL){
		/*
		rotulo
		urlToSend
			urlToCapa
			urlProjeto
		descriptionFile
		extencoesValidas
		*/
		//echo "<li>urlToSend $urlToSend</li>";
		$arrayParametros = array();
		$arrayParametros[] = array("rotulo", 			($rotulo));
		//rotuloEnviar
		$arrayParametros[] = array("urlToSend", 		str_replace(array("&", "="), array("[@]", "[.]"), $urlToSend));
		$arrayParametros[] = array("urlToGetThumb", 	str_replace(array("&", "="), array("[@]", "[.]"), $urlToGetThumb));
		$arrayParametros[] = array("descriptionFile", 	($descriptionFile));
		$arrayParametros[] = array("extencoesValidas", ($extencoesValidas));
		$arrayParametros[] = array("titulo", 			($titulo));
		global $URL;
		$arrayParametros[] = array("urlProjeto", 		$URL);
		$arrayParametros[] = array("temCapa", 			"1");
		
		if($urlToCapa !== NULL){ 
			$arrayParametros[] = array("urlToCapa", str_replace(array("&", "="), array("[@]", "[.]"), ($urlToCapa)));
		}
		if($urlToGet !== NULL){
			$arrayParametros[] = array("urlToGetLista", str_replace(array("&", "="), array("[@]", "[.]"), ($urlToGet)));
		}
		if($urlToDelete !== NULL){
			$arrayParametros[] = array("urlToDelete", 	str_replace(array("&", "="), array("[@]", "[.]"), ($urlToDelete)));
		}
		if($funcaoNoTermino !== NULL){
			$arrayParametros[] = array("funcaoNoTermino", 	$funcaoNoTermino);
		}
		
		if($limiteDeFotos!== NULL){
			$arrayParametros[] = array("limiteDeFotos", 	$limiteDeFotos);
		}
		return DataHandler::addSimpleFlash(400, 330, "template/_swf/envio_foto3.swf", $arrayParametros, false, "#FFFFFF");
	}
	/**
	 * envie a array e o nome do nÃ³ da array que vc precisa e ele retorna o valor ou NULL se esse nÃ³ nÃ£o existir
	 * evitando o erro de campos que nÃ£o foram enviados por POST por exemplo, ou que nÃ£o existem numa array
	 * @param array $p_array
	 * @param string $node_name
	 * @return value or NULL
	 */
	public static function getValueByArrayIndex($p_array, $node_name, $default_value = NULL){
		return (isset($p_array[$node_name]))?$p_array[$node_name]:$default_value;
	}
	public static function getValueByStdObjectIndex($p_obj, $node_name){
		return (isset($p_obj->$node_name))?$p_obj->$node_name:NULL;
	}
	public static function cleanArrayEmpyIndex($array){
        $rt = array();
        for ($i = 0; $i<sizeof($array) ; $i++ )
            if(!empty($array[$i]))
                $rt[] = $array[$i];
                
        return $rt;
    }
	
	
	/*
	 * recebe um float ou string no formato do banco e converte para a exibiÃ§Ã£o nas views
	 * converte para 1 casa decimal, mesmo quando nÃ£o Ã© necessÃ¡rio
	 */
	public static function convertNumberToString1Decimal($data){
		
		$data = str_replace('.', ',', $data);

		$data =  substr($data, 0 , strpos($data,',')+2);
		
		if( substr($data, strpos($data,',')+1) == 0 ){
			
			$data =   substr($data,0 ,strpos($data,','));
		} 
		return $data;	
	}
	
	public static function cropString( $string, $limit ,$default_continue = '...'){
		if(strlen($string)<= $limit )
			return $string;
		return substr($string, 0, $limit) . $default_continue;
	}
	/**
	 * @param $array array original
	 * @param $item_or_array que deve ser adicionado ao fim
	 * @return array jÃ¡ com os itens inclusos no final
	 */
	public static function appendArray($array, $item_or_array){
		if(is_array($array)){
			if(is_array($item_or_array)){
				foreach($item_or_array as $item){
					$array[] = $item;
				}
			} else {
				$array[] = $item_or_array;
			}
		}
		return $array;
	}
}