<?php

/**
 * @desc VO de apoio da controller FacilMail
 * @author Mauricio Amorim
 *
 */
//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

class MailInfoPostVO{
	/** @desc seu servidor SMTP */
	public $host;
	/** @desc true para autenticação */
	public $smtp_auth = TRUE;
	/** @desc email a ser utilizado no envio da mensagem */
	public $username;
	/** @desc senha para acessar o email */
	public $password;
	/** @desc email que deve aparecer quando a mensagem for enviada */
	public $from;
	/** @desc nome de quem esta enviando a mensagem */
	public $from_name;
	/** @desc email de que vai receber a mensagem */
	public $to;
	/**
	 * @desc se o conteudodo do email é html
	 * @var $boolean 
	 */
	public $is_html;
	/** @desc titulo do email */
	public $subject;
	/** @desc emails a ser enviado como copia */		
	public $add_address;
	/** @desc charset do email */
	public $char_set;	
	/** @desc aqui vai o html pronto a ser enviado no corpo do email */
	public $body;
	
	
	/** @desc Atenção tem que passar true no setAntiSpam(true) na MailInfoPostVO para que o email possa ser enviado */
	private $anti_spam = FALSE;
	
	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
	
	/**
	 * @param $boolean
	 * @return void
	 */
	public function setAntiSpam($boolean){
		if($boolean = TRUE){
			$this->anti_spam = TRUE;
		}
	}
	
	/**
	 * @return boolean
	 */
	public function getAntiSpam(){
		return	$this->anti_spam;
	}
	
	public function setFetchArray($array){
		//print_r($array);
		$this->host				= DataHandler::getValueByArrayIndex($array, "host");
		$this->smtp_auth		= (DataHandler::getValueByArrayIndex($array, "smtp_auth") == NULL)?FALSE:TRUE;
		$this->username			= DataHandler::getValueByArrayIndex($array, "username");
		$this->password			= DataHandler::getValueByArrayIndex($array, "password");
		$this->from				= DataHandler::getValueByArrayIndex($array, "from");
		$this->from_name		= DataHandler::getValueByArrayIndex($array, "from_name");
		$this->to				= DataHandler::getValueByArrayIndex($array, "to");
		$this->is_html			= (DataHandler::getValueByArrayIndex($array, "is_html") == NULL)?FALSE:TRUE;
		$this->subject			= DataHandler::getValueByArrayIndex($array, "subject");;
		$this->add_address		= DataHandler::getValueByArrayIndex($array, "add_address");
		$this->body 			= DataHandler::getValueByArrayIndex($array, "body");

		if(DataHandler::getValueByArrayIndex($array, "char_set") != NULL){
			switch(strtolower(DataHandler::getValueByArrayIndex($array, "char_set"))){
				case "iso-8859-1":
				 $this->char_set = "ISO-8859-1";
				 break;
				case "utf-8":
				default:
				 $this->char_set = "UTF-8";
				 break;
			}
		}
	}

	/**
	 * @desc Validação da VO para ver se está apta a ser cadastrada
	 * @return ReturnResultVO
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if(!Validation::validateEmail($this->from)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_FROM_EMAIL"));
		}
		if(!Validation::validateEmail($this->to)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_TO_EMAIL"));
		}
		if(!Validation::blank($this->from_name, 2)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_NAME"));
		}
		if(!Validation::blank($this->body, 2)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_MESSAGE"));
		}
		if(!Validation::blank($this->host, 8)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_HOST"));
		}
		if(!Validation::blank($this->username, 2)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_USERNAME"));
		}
		if(!Validation::blank($this->password, 2)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_INVALID_PASSWORD"));
		}
		return $ReturnResultVO;
	}

}