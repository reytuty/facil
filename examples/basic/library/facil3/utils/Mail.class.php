<?php

class Mail {
	private $smtp;
	private $to 	 = '';
	private $from 	 = '';
	private $cc 	 = '';
	private $bcc 	 = '';
	private $message = '';
	private $subject = '';
	function __construct($smtp = NULL, $user = NULL, $senha = NULL) {
		if($smtp){
			$this->smtp = new Smtp($smtp);
			$this->smtp->user  = $user;
			$this->smtp->pass  = $senha;
			$this->smtp->debug = false;
		}
	}
	public function addTo($email, $nome = '') {
		$this->to .= ( $this->to == '' ) ? '' : ', ';
		$this->to .= ( $nome == '' ) ? $email : $nome.' <'.$email.'>';
	}
	public function setTo($email, $nome = '') {
		$this->to = ( $nome == '' ) ? $email : $nome.' <'.$email.'>';
	}
	public function setFrom($email, $nome = ''){
		$this->from = ( $nome == '' ) ? $email : $nome.' <'.$email.'>';
	}
	public function addCc($email, $nome = '') {
		$this->cc .= ( $this->cc == '' ) ? '' : ', ';
		$this->cc .= ( $nome == '' ) ? $email : $nome.' <'.$email.'>';
	}
	public function addBcc($email, $nome = '') {
		$this->bcc .= ( $this->bcc == '' ) ? '' : ', ';
		$this->bcc .= ( $nome == '' ) ? $email : $nome.' <'.$email.'>';
	}
	public function setMessage($message){
		$this->message = $message;
	}
	public function setSubject($subject){
		$this->subject = $subject;
	}
	private function buildHeader() {
		$header  = 'MIME-Version: 1.0' . "\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
	if ( $this->to != '' )
		$header .= 'To: '.$this->to.'' . "\n";
	if ( $this->from != '' )
		$header .= 'From: '.$this->from.'' . "\n";
	if ( $this->cc != '' )
		$header .= 'Cc: '.$this->cc.'' . "\n";
	if ( $this->bcc != '' )
		$header .= 'Bcc: '.$this->bcc.'' . "\n";
	return $header;
	}
	public function send($continue = FALSE) {
		$header = $this->buildHeader();
		if($this->smtp){
			return $this->smtp->Send($this->to, $this->from, $this->subject, $this->message, $continue);
		} else {
			return mail($this->to, $this->subject, $this->message, $header);
		}
	}
	public function validar(){
		$email = $this->from;
		return ereg("(.+)[^\.]@(.+)([[:punct:]])(.+)", $email, $arrayT);
	}
}

class Smtp {
	private $conn;
	public $user;
	public $pass;
	public $debug = true;
	function Smtp($host){
		$this->conn = fsockopen($host, 587, $errno, $errstr, 30);
			$this->Put("EHLO $host");
	}
	private function Auth(){
			$this->Put("AUTH LOGIN");
			$this->Put(base64_encode($this->user));
			$this->Put(base64_encode($this->pass));
	}
	public function Send($to, $from, $subject, $msg, $continue = FALSE){
		$this->Auth();
		$this->Put("MAIL FROM: " . $from);
		$this->Put("RCPT TO: " . $to);
		$this->Put("DATA");
		$this->Put($this->toHeader($to, $from, $subject));
		$this->Put("\r\n");
		$this->Put($msg);
		$this->Put(".");
		if(!$continue){
			$this->Close();
		}
		return (isset($this->conn));
	}
	private function Put($value){
		return fputs($this->conn, $value . "\r\n");
	}
	private function toHeader($to, $from, $subject){
		$data1_str = @date('YmdHis');
		$header  = "Message-Id: <". $data1_str.".". md5(microtime()).".". strtoupper($from) ."> \r\n";
				$header .= "From: " . $from . " \r\n";
				$header .= "To: <".$to."> \r\n";
				$header .= "Subject: ".$subject." \r\n";
				$data_str = @date('D, d M Y H:i:s O');
				$header .= "Date: ". $data_str ." \r\n";
				$header .= "X-MSMail-Priority: High \r\n";
				return $header;
	}
	private function Close(){
		$this->Put("QUIT");
		if($this->debug == true){
				while (!feof ($this->conn))
				echo fgets($this->conn) . "<br>\n";
	}
	return fclose($this->conn);
	}

}

