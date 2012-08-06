<?php

include_once("library/facil3/navigation/http/HttpResult.class.php");

//include_once("library/forum/modules/contact/dao/ContactDAO.class.php");
//include_once("library/forum/modules/contact/vo/ContactVO.class.php");

include_once("facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView("/HttpRoot.class.php")));

include_once "library/facil3/core/controller/mail/MailInfoPostVO.php" ;
include_once "library/facil3/utils/Mail.class.php";
//para retorno do envio e cadasro de mail
include_once "library/facil3/core/vo/ReturnResultVO.class.php";

/**
 * @author 		: Mauricio Amorim
 * @date		: 1/01/2011
 * @version		: 1.0 (1.0 seria se a pessoa tivesse testado e funcionou)
 * @desc		: Controller de contato onde extende a controller mail
 */

/**
 * @author Renato
 * @date		: 19/01/2011
 * @version		: 1.0
 * @desc		: 
 */
class Contato implements HTTPControllerInterface  {
	//configurações e dados a serem a serem enviados no email
	public $MailInfoPost;
	
	public $arrayVariable;
	public $arrayRestFolder;
	
	public function __construct($arrayRestFolder = NULL){
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
        //http://www.espacolaser.com.br/aprovacao/contato/insert
	}
	
    /*
     * naum faz nada pq é só para mostrar o html
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
    
    /*
     * rescebe os dados por post e envia e manda por email atravez da ccontroller FacilMail
     * @return ReturnResultVO
     */
    public function insert(){
    	//inicia um retorno de ReturnResultVO
    	$ReturnResultVO = new ReturnResultVO();
		/*
	[nome] => fdsafas
    [email] => afsdafsa@fdsf.com
    [telefone] => dfdsfds
    [cidade] => fdsfsfs
    [estado] => fdsfds
    [mensagem] => fsfa
    [submit] => Enviar
		 */
    	$nome 		= DataHandler::forceString(DataHandler::getValueByArrayIndex($_POST, "nome"), TRUE, TRUE);
    	$email 		= DataHandler::getValueByArrayIndex($_POST, "email");
    	$telefone 	= DataHandler::forceString(DataHandler::getValueByArrayIndex($_POST, "telefone"), TRUE, TRUE);
    	$cidade 	= DataHandler::forceString(DataHandler::getValueByArrayIndex($_POST, "cidade"), TRUE, TRUE);
    	$mensagem 	= DataHandler::forceString(DataHandler::getValueByArrayIndex($_POST, "mensagem"), TRUE, TRUE);
    	$estado 	= DataHandler::forceString(DataHandler::getValueByArrayIndex($_POST, "estado"), TRUE, TRUE);
    	
    	if(!Validation::validateEmail($email)){
    		//faz nada, o email ta errado
    		echo "email errado";
    		exit();
    	}
    	$body = " 
nome: $nome <br />
telefone : $telefone <br />    	
email : $email <br />
cidade: $cidade <br />
estado: $estado <br />
    	----------------
    	<br /><br />
mensagem : $mensagem ";
		$Mail = new Mail();
    	//preciso por no config o email de destino
    	$Mail->setTo("contato@espacolaser.com.br", "Espaço Laser");
    	$Mail->setFrom($email, $nome);
    	$Mail->setMessage($body);
    	$Mail->setSubject("mensagem enviada pelo site");
    	@$Mail->send();
    	Navigation::redirect("contato");
    	exit();
    }
}