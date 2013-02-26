<?php

/**
 * @desc VO de apoio da controller NakedCaptcha
 * @author Mauricio Amorim
 *
 */
/**
 * @author Renato
 * @date		: 19/01/2011
 * @version		: 1.0
 * @desc		: 
 */
class NakedCaptchaInfoPostVO{
	
	/**
	 * @desc codigo criptografado
	 */
	public $captcha_code;

	/**
	 * @desc resposta de um imagecreatefromjpeg()
	 */
	public $captcha_image;

	/**
	 * @desc campo prenchido pelo usuario
	 */
	public $captcha_value;

	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
		
	public function setFetchArray($array){
		//print_r($array);
		$this->captcha_code			= DataHandler::getValueByArrayIndex($array, "captcha_code");
		$this->captcha_image		= DataHandler::getValueByArrayIndex($array, "captcha_image");
		$this->captcha_value		= DataHandler::getValueByArrayIndex($array, "captcha_value");
	}

}