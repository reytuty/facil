<?php
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Utilizada como objeto padrão para meta tags em VIEWS.
 * @Obs Essa Classe deve ser iniciada e manipulada obrigatóriamente apenas pela HttpContent.class.php
 **/
include_once("library/facil3/utils/DataHandler.class.php");
class HttpHeader{

	protected $title;
	protected $keywords;
	protected $description;

	// ------------------------------------- SETs	
	/**
	 * @param $title (str)
	 */
	function setTitle($title){
		$this->title = DataHandler::forceString($title);
	}
	/**
	 * @param $keywords (str)
	 */
	function setKeywords($keywords){
		$this->keywords = DataHandler::forceString($keywords);
	}
	/**
	 * @param $description (str)
	 */
	function setDescription($description){
		$this->description = DataHandler::forceString($description);
	}
	
	
	// ------------------------------------- GETs
	/**
	 * @return $title (str)
	 */
	function getTitle(){
		return $this->title;
	}
	/**
	 * @return $keywords (str)
	 */
	function getKeywords(){
		return $this->keywords;
	}
	/**
	 * @return $description (str)
	 */
	function getDescription(){
		return $this->description;
	}
}