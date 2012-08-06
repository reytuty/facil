<?php
class Validation{
	/**
	 * @param $variable
	 * @return boolean
	 */
	static function validateCNPJ($variable){
		$retorno = false;
		if((strlen($variable) <> 14)){
			$variable = str_replace('.', '', $variable);
			$variable = str_replace('/', '', $variable);
			$variable = str_replace('-', '', $variable);
			if((strlen($variable) <> 14)){
				for($i = 1; $i < 15; $i++){
					$cnpj .= substr($variable,$i,1);
				}
			} else {
				$cnpj = $variable;
			}
		} else {		
			$cnpj = $variable;
		}
		if(!is_numeric($cnpj) or strlen($cnpj) <> 14){
			return $return;
		}
		$i = 0;
		while($i < 14){
			$cnpj_d[$i] = substr($cnpj,$i,1);
			$i++;
		}
		$dv_ori = $cnpj[12] . $cnpj[13];
		$soma1 = 0;
		$soma1 = $soma1 + ($cnpj[0] * 5);
		$soma1 = $soma1 + ($cnpj[1] * 4);
		$soma1 = $soma1 + ($cnpj[2] * 3);
		$soma1 = $soma1 + ($cnpj[3] * 2);
		$soma1 = $soma1 + ($cnpj[4] * 9);
		$soma1 = $soma1 + ($cnpj[5] * 8);
		$soma1 = $soma1 + ($cnpj[6] * 7);
		$soma1 = $soma1 + ($cnpj[7] * 6);
		$soma1 = $soma1 + ($cnpj[8] * 5);
		$soma1 = $soma1 + ($cnpj[9] * 4);
		$soma1 = $soma1 + ($cnpj[10] * 3);
		$soma1 = $soma1 + ($cnpj[11] * 2);
		$rest1 = $soma1 % 11;
		if($rest1 < 2){
			$dv1 = 0;
		} else {
			$dv1 = 11 - $rest1;
		}
		$soma2 = $soma2 + ($cnpj[0] * 6);
		$soma2 = $soma2 + ($cnpj[1] * 5);
		$soma2 = $soma2 + ($cnpj[2] * 4);
		$soma2 = $soma2 + ($cnpj[3] * 3);
		$soma2 = $soma2 + ($cnpj[4] * 2);
		$soma2 = $soma2 + ($cnpj[5] * 9);
		$soma2 = $soma2 + ($cnpj[6] * 8);
		$soma2 = $soma2 + ($cnpj[7] * 7);
		$soma2 = $soma2 + ($cnpj[8] * 6);
		$soma2 = $soma2 + ($cnpj[9] * 5);
		$soma2 = $soma2 + ($cnpj[10] * 4);
		$soma2 = $soma2 + ($cnpj[11] * 3);
		$soma2 = $soma2 + ($dv1 * 2);
		$rest2 = $soma2 % 11;
		if($rest2 < 2){
			$dv2 = 0;
		} else {
			$dv2 = 11 - $rest2;
		}
		$dv_calc = $dv1 . $dv2;
		if($dv_ori == $dv_calc){
			$return = $cnpj = substr($cnpj, 0, -12).".".substr($cnpj, -12, 3).".".substr($cnpj, -9, 3)."/".substr($cnpj, -6, 4)."-".substr($cnpj, -2);
		}
		$return = false;
	} 
	/**
	 * @param $cpf
	 * @return array (bool, string)
	 */
	static function validateCPF($cpf){
		@$cpf = ereg_replace("[^0-9]", "", $cpf);
		if (strlen($cpf) > 11){
			if(strlen($cpf) > 11){
				return array(FALSE, "muitos digitos");
			}
		} else if(strlen($cpf) < 10){
			return array(FALSE, "muito curto");
		}
		if (!is_numeric($cpf)){
			return array(FALSE, "apenas números são aceitos em cpf");
		} else {
			if ($cpf == "00000000000" or
				$cpf == "11111111111" or
				$cpf == "22222222222" or
				$cpf == "33333333333" or
				$cpf == "44444444444" or
				$cpf == "55555555555" or
				$cpf == "66666666666" or
				$cpf == "77777777777" or
				$cpf == "88888888888" or
				$cpf == "99999999999")
			{
				return array(FALSE, "cpf incorreto, numero obvio.");
			}
			$b = 0;
			$c = 11;
			for ($i=0; $i<11; $i++){
				$a[$i] = substr($cpf, $i, 1);
				if ($i < 9){
					$b += ($a[$i] * --$c);
				}
			}
			if (($x = $b % 11) < 2){
				$a[9] = 0;
			} else {
				$a[9] = 11-$x;
			}
			
			$b = 0;
			$c = 11;
			for ($y=0; $y<10; $y++){
				$b += ($a[$y] * $c--);
			}
			if (($x = $b % 11) < 2){
				$a[10] = 0;
			} else {
				$a[10] = 11-$x;
			}
			if ((substr($cpf, 9, 1) != $a[9]) or (substr($cpf, 10, 1) != $a[10])){
				return array(FALSE, "erro  no cpf");
			}
		}
		$cpf = substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9, 2);
		return array(TRUE, $cpf);
	}
	/**
	 * @param $valor string or number
	 * @return boolean
	 */
	static function isNumber($valor){
		return is_numeric($valor);
	}
	static function validateCEP($cep){
		$return = false;
		$cep = str_replace('-', '', $cep);
		if(strlen($cep) == 8){
			if (is_numeric($cep)){
				$return = $cep = substr($cep, 0, 5).'-'.substr($cep, 5, 3);
			}
		}
		return $return;
	}
	/**
	 * @param $email
	 * @return boolean
	 */
	static function validateEmail($email){
		$temp_array = array();
		if(@ereg("([a-zA-Z0-9_-]+[a-zA-Z0-9_.-]*@[a-zA-Z0-9_-]+[a-zA-Z0-9_.-]*\.[a-zA-Z]{2,5})", $email, $temp_array)){
			return true;
		}
		
		return false;
	}
	
	/**
	 * @param $email
	 * @return array ou false
	 */
	static function validateEmailReturnArray($email){
		$temp_array = array();
		if(ereg("[a-z0-9_-]+[a-z0-9_.-]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}", $email, $temp_array)){
			return $temp_array;
		}
		return false;
	}
	/**
	 * @param $data
	 * @return array ou false
	 */
	static function validateDate($data){
		$arrayData = array();
		if(ereg("([0-9]?[0-9])[/\\-]([0-9]?[0-9])[/\\-]([0-9]?[0-9]?[0-9][0-9])", $data, $arrayData)){
			return $arrayData;
		}
		return false;
	}
	/**
	 * @param $url
	 * @return boolean
	 */
	static function validateUrl($url){
		//testar e ver se est� ok essa fun��o
		return ereg("http://w?w?w?\.?[a-zA-Z_0-9-]+\.[a-zA-Z_0-9-][a-zA-Z_0-9\.-]+", $url);
	}
	/**
	 * @param $texto
	 * @return boolean
	 */
	static function blank($text, $limit = 1){
			return (strlen($text) >= $limit);// se tiver mais de 1 caractere ta ok
	}
}
/*
$validate = new Validation();
validateEmail($email)
$errado = $validate->validateCPF("6546546546");
var_dump($errado);
echo "<hr>";
$certo = $validate->validateCPF("302.095.808-36");
var_dump($certo);

$validate = new Validation();
$certo = $validate->validateEmail("diego@midianova.com.br");
var_dump($certo);
echo "<hr>";
$errado = $validate->validateEmail("fdsaf32ef3#@3");
var_dump($errado);
*/
?>