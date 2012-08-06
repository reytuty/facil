<?php 

/**
 * @author Mauricio Amorim
 * @desc interface do gerador de codigo para criação de imagem e validadação de captcha
 *
 */
interface EncryptCaptcha {
	
	/**
	 * @return string
	 * @desc gera um codigo pra depois ser usado na criação da imagem e validação do captcha
	 */
	static function generate();
	
	/**
	 * @param $encryptCode essa variavel veio do metodo generate
	 * @param $value essa variavel vem da digitação do usuario
	 * @return boolean
	 */
	static function validate($encryptCode, $value);
}
