<?php
/**
 * @author 		: Mauricio Amorim
 * @date		: 05/12/2010
 * @version		: 1.0
 * @desc		: Controller que envia e-mail
 * 					Atenção tem que passar true no setAntiSpam(true) na MailInfoPostVOVO para que o email possa ser enviado
 */
//para retorno
include_once "library/facil3/core/vo/ReturnResultVO.class.php";
//classe de envio de email
include_once("library/facil3/core/modules/phpmailer/class.phpmailer.php");

class FacilMail implements HTTPControllerInterface {
	/** @desc configurações e dados a serem a serem enviados no email */
	public $MailInfoPostVO;
	
	public $arrayVariable;
	public $arrayRestFolder;
	
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
        //por padrão ele popula as infos que ele possui com o que veio na restFolder, pode ser resetado utilizando resetInfoPost
        $this->MailInfoPostVO = new MailInfoPostVO($this->arrayVariable);
	}

    /**
     * @desc naum faz nada pq é só para mostrar o html
     */
    public function init(){
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$returnResult->setSuccess(TRUE);
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);
		return $returnResult;
    }

    /**
     * passe uma nova MailInfoPostVO caso não queira usar o que está na rest folder
     * @param MailInfoPostVO $MailInfoPostVO
     * @return void
     */
    public function resetInfoPost(MailInfoPostVO $MailInfoPostVO = NULL){
    	$this->MailInfoPostVO = $MailInfoPostVO;
    }
    
    /**
     * @desc envia email atravez da configuração passada no MailInfoPostVOVO
     * @return ReturnResultVO
     */
    public function mail(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
    	$RuturnResultVO = $this->MailInfoPostVO->validate();
    	if(!$RuturnResultVO->success){
    		return $RuturnResultVO;
    	}		
	
		$mail = new PHPMailer();
	
		$mail->IsMail();
		$mail->CharSet = $this->MailInfoPostVO->char_set;
		//$mail->IsSMTP(); // send via SMTP
		$mail->Host = $this->MailInfoPostVO->host; //seu servidor SMTP
		$mail->SMTPAuth = $this->MailInfoPostVO->smtp_auth; // true para autenticação
		$mail->Username = $this->MailInfoPostVO->username; // usuÃ¡rio de SMTP
		$mail->Password = $this->MailInfoPostVO->password; // senha de SMTP
		
		$mail->From = $this->MailInfoPostVO->from;
		//coloque aqui o seu correio, para que a autenticaÃ§Ã£o nÃ£o barre a mensagem
		$mail->FromName = $this->MailInfoPostVO->from_name;
		
		$mail->WordWrap = 50; // Definiçao de quebra de linha
		
		// envio como HTML se é true
		if($this->MailInfoPostVO->is_html){
			$mail->IsHTML(TRUE); 
		}
		
		$mail->Subject = $this->MailInfoPostVO->subject;
		
		$mail->Body = $this->MailInfoPostVO->body;
		//$mail->AltBody = $msgFinal;
	
		//Destinatarios
		$mail->AddAddress($this->MailInfoPostVO->to);
		$mail->AddAddress($this->MailInfoPostVO->add_address);
		//envia email atravez do phpmailer
		if(@$mail->Send()){
			$RuturnResultVO->success = TRUE;
			$RuturnResultVO->addMessage(Translation::text("LibraryLanguage::SUCCESS_CONTACT_SEND_EMAIL"));
			return $RuturnResultVO;
		}else{
			$RuturnResultVO->success = FALSE;
			$RuturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CONTACT_SEND_EMAIL"));
			return $RuturnResultVO;
		}

    }
}