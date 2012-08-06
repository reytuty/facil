<?php
	/*
	 * @author		: Diego Faria Montania
	 * @data		: 07/07/2009
	 * @version		: 1.0
	 * @description	: 	Classe para auxilio de debugs
	 					Modo de uso
							primeiro:
								de o include_once da classe
							segundo:
								//se quiser que de echo
								Debug::li("testando");
								//se quiser guardar o debug em variavel
								$teste = Debug::li("testando com retorno", TRUE);
	 */
class Debug{
	static function li($text = "", $return = FALSE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor = "#003300"){
		$text = "<hr /><div style=\"padding:2px; min-width:500px; background-color:$bgColor;border:2px; border-color:$borderColor; color:$fontColor; font-family:Arial, Helvetica, sans-serif;\">
	$text
</div>";
		if($return){
			return $text;
		}
		echo $text;
	}
	static function pre($obj, $return = FALSE){
		$text = "<pre>\n";
		$text .= var_export($obj, TRUE);
		$text .= "\n</pre>\n";
		$bgColor = "#003300";
		if(isset($obj->success)){
			if($obj->success){
				$bgColor = "#000033";
			} else {
				$bgColor = "#CC0000";
			}
		}
		if($return){
			return Debug::li($text, TRUE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
		}
		echo Debug::li($text, FALSE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
	}
	static function print_r($obj, $return = FALSE){
		$text = "<pre style='font-family:verdana; white-space:pre !important; text-align: left;'>\n";
		$text .= print_r($obj, TRUE);
		$text .= "\n</pre>\n";
		$bgColor = "#003300";
		if(isset($obj->success)){
			if($obj->success){
				$bgColor = "#000033";
			} else {
				$bgColor = "#CC0000";
			}
		}
		if($return){
			return Debug::li($text, TRUE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
		}
		echo Debug::li($text, FALSE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
	}
	static function _forEach($obj, $return = FALSE){
		$result = "";
		foreach($obj as $key=>$valor){
			if($return){
				$result .= "<br /> [$key]  = {".Debug.print_r($valor, TRUE)."}";
			} else {
				echo "<br /> obj[$key] ";
			}
		}
		if($return){
			return $result;
		}
		echo $result;
	}
}

?>