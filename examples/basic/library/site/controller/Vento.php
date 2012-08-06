<?php

class Vento{
	public $arrayRestFolder;
	public $arrayVariable;
	public function __construct($arrayRestFolder){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	public function init(){
		if(isset($_GET["url"])){
			echo $this->getUrl();
		}
		exit();
	}
	public function getUrl(){
		//return file_get_contents("http://ven.to/?url=".urlencode($_GET["url"]));
		return urlencode($_GET["url"]) ;
	}
	public function goToTwitter(){
		$url = $this->getUrl();
		header("Location: http://twitter.com/home/?status=".$url);
	}
	public function goToFacebook(){
		$url = $this->getUrl();
		header("Location: http://www.facebook.com/share.php?u=".$url);
	}
}