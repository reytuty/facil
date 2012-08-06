<?php 

/**
 * @author Mauricio Amorim
 * @desc interface de gerador e de imagem captcha
 *
 */
interface ImageCaptcha {
	
	/**
	 * @param $encryptCode essa variavel veio do metodo generate da classe EncryptCaptcha
	 * @desc exibe uma imagem conforme o codigo passado como parametro
	 * @return void
	 */
	static function generate($encryptCode);
	
	/**
	 * @param $image imagecreatefromjpeg($backgoundImage)
	 * @desc exibe uma imagem conforme o codigo passado como parametro
	 * @return void
	 */
	static function show($image);
}
