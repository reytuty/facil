<?php 
//inclui a interface
include_once "library/facil3/core/module/captcha/interface/EncryptCaptcha.interface.php";

/**
 * @author Mauricio Amorim
 * @desc Classe que contem gerador e validador de captcha segundo criptografia da classe NakedEncrypt
 * 
  *  - essa controler gera codigo para gerar imagem de captcha, uma imagem com 6 caracteres com um codigo passado
 *  segundo as regras e confirmação de validação atravez do codigo da imagem e o campo preenchido no formulario.
 *  
 *  - o codigo a ser passado para gerar a imagem e passar como verificação no compo imput
 *  deve ser a mutiplicação por 2 de seis letras transformadas uma por uma em numeros da tabela ASCII
 *  
 *  - a string da imagem deve ser gerada dividindo o codigo passado por url por 2 e trasformado os
 *  numeros de dois em dois em caracteres atraves da função ord() do php
 *  
 *  - a regra para validação do captcha é pegar os caracteres da imagem um por um e transforma-los
 *  em numeros da tabela ASCII atravez da função ord() do php, multiplicar por 2 e comparar com o codigo 
 *  passado por imput  
 *  
 */
class NakedEncrypt implements EncryptCaptcha{
	
	/**
	 * @return string
	 * @desc gera um codigo criptografado para posteriormente gerar uma imagem e validar
	 */
	static function generate(){
		$captcha_code = NULL;
		// Crio uma string com 6 caracteres seus respectivos lugares e fontSize de letra na imagem
		for ($i = 0; $i < 6 ;$i++) {
			// Seleciona de 0 à 2, onde 0 = letra maiúscula, 1 = minúscula e 2 = número.
			$tipo = rand(0,2);
			switch($tipo) {
				// se pegou 0 ele cria uma letra maiúscula de A à Z.
				// com um porém, o chr(rand(65,90)), a função chr retorna um caractere específico, e o rand seleciona aleatório.
				// o motivo de estar rand(65,90), pois temos que usar de acolordo com a tabela ASCII e o A = 65 e o Z = 90.
				case 0 : 
					// mesma coisa que antes, mais aqui é maiusculo.
					//naum vai ser usado até o numero 122 pq a logica do codigo só usa até a casa
					//da dezena.
					$int = rand(97,99);
					break;
				case 1 : 
					// mesma coisa que antes, mais aqui é minúsculo.
					//naum vai ser usado até o numero 65 pq para essas letras usarei maiusculas
					$int = rand(68,90);
				break;
				case 2 : 
					// mesma coisa que antes só que aqui são números.
					$int = rand(48,57);
					break;
				// caso ocolorra algun erro no rand tipo ele para por aqui.
				default : 
					break;
			}
			
			// crio a codigo que sera usado para gerar imagem e passar como imput para validação
			$captcha_code .= $int;
		}
		return $captcha_code;		
	}

	/**
	 * @param $encryptCode essa variavel veio do metodo generate
	 * @param $value essa variavel vem da digitação do usuario
	 * @return boolean
	 * @desc para validação converto cada caracter da string $value em codigo ASCII e multipico por dois para
	 * depois comparar com o $encryptCode
	 */
	static function validate($encryptCode, $value){
		// converto cada caracter da string $value em codigo ASCII e multipico por dois para
		// depois comparar com o $encryptCode
		for($i = 1; $i < strlen($captcha_code)/3 ;$i++){
			$code = chr($captcha_code[$i].$captcha_code[$i+1]);
		}
		//multiplico por 2 para depois realizar a comparação com o codigo passado
		$code = $code * 2;
		//comparo com o $encryptCode
		return ($encryptCode == $code)?TRUE:FALSE;
	}
}