<?php
	/**
	 * @author		: Eric Mantoani <eu@eric.com.br>
	 * @date		: 18/07/2010
	 * @version		: 1.0
	 * @tabela		: file
	 * @description		: 
	 * 					Qualquer filem que for enviada pro servidor, vem pra c�.
	  						id, 
	  						active, 
	  						type, 
	  						url,
	  						name,
	  						description,
	  						order,
	  						locale,
	  						author
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
include_once "library/facil3/core/modules/file/dao/FileDAO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";

class FileVO extends BaseVO implements PatternVO{
	var $name;
	var $active;
	var $type; 
	var $url;
	var $description;
	var $order;
	var $locale;
	var $author;
	
	private $file_name;
	const TERM_NAME 		= "name";
	const TERM_DESCRIPTION	= "description";
	
	function __construct($arrayFetchPost = NULL){
		//inicia funcao de validacao
		parent::_construct();
		$this->__table = "file";
		//para as VOs que pretendem poder ter parametro, incluir a tabela de referencia
		//se enviar $arrayFetchPost � para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->fetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = FileDAO::getInstance();
	}
		/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->name = DataHandler::getValueByArrayIndex($array_dados, "name");
		$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		$this->description = DataHandler::getValueByArrayIndex($array_dados, "description");
		$this->type = DataHandler::getValueByArrayIndex($array_dados, "type");
		$this->url = DataHandler::getValueByArrayIndex($array_dados, "url");
		$this->order = DataHandler::getValueByArrayIndex($array_dados, "order");
		$this->author = DataHandler::getValueByArrayIndex($array_dados, "author");
		$this->locale = DataHandler::getValueByArrayIndex($array_dados, "locale");
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->name = DataHandler::getValueByStdObjectIndex($obj_dados, "name");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->description = DataHandler::getValueByStdObjectIndex($obj_dados, "description");
		$this->type = DataHandler::getValueByStdObjectIndex($obj_dados, "type");
		$this->url = DataHandler::getValueByStdObjectIndex($obj_dados, "url");
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order");
		$this->author = DataHandler::getValueByStdObjectIndex($obj_dados, "author");
		$this->locale = DataHandler::getValueByStdObjectIndex($obj_dados, "locale");
	}
	//---------------SETs
	public function setActive($active){
		$this->active = DataHandler::forceInt($active);
	}

	/**
	 * @param $name
	 * @param $locale
	 * 
	 */
	public function setName($name, $locale = NULL){
		if($locale == NULL){
			$this->name = DataHandler::forceString($name);
		} else {
			$this->addTherms(FileVO::TERM_NAME, $locale, $name);
		}
	}
	
	/**
	 * @param $description
	 * @param $locale
	 * 
	 */
	public function setDescription($description, $locale = NULL){
		if($locale == NULL){
			$this->description = DataHandler::forceString($description);
		} else {
			$this->addTherms("description", $locale, $description);
		}
	}
	
	/**
	 * @param $type
	 * 
	 */
	public function setType($type){
		$this->type = DataHandler::forceString($type);
	}
	
	/**
	 * @param $url
	 * 
	 */
	public function setUrl($url){
		$this->url = DataHandler::forceString($url);
	}
	
	/**
	 * @param $order
	 * 
	 */
	public function setOrder($order){
		$this->order = DataHandler::forceInt($order);
	}
	
	
	/**
	 * @param $author
	 * 
	 */
	public function setAuthor($author){
		$this->author = DataHandler::forceString($author);
	}
	
	/**
	 * @param $locale
	 * 
	 */
	public function setLocale($locale){
		$this->locale = DataHandler::forceString($locale);
	}
	
	
	// ---------------------------GETs
	/**
	 * @return int
	 */
	public function getActive(){
		return $this->active;
	}
	
	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale === NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(FileVO::TERM_NAME, $locale);
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
	
	/**
	 * @param $locale
	 * @return str
	 */
	public function getDescription($locale = NULL){
		if($locale === NULL){
			return $this->description;
		} else {
			$tempResult = $this->getStringTherm(FileVO::TERM_DESCRIPTION, $locale);
			return ($tempResult == "")?$this->description:$tempResult;
		}
	}

	/**
	 * @return str
	 */
	public function getType(){
		return $this->type;
	}
	
	/**
	 * @return str
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * retorna o nome do arquivo na url
	 * @return str
	 */
	public function getFileName(){
		if(!$this->file_name){
			if($this->url){
				$array = explode("/", $this->url);
				$this->file_name = $array[count($array)-1];
			}
		}
		return $this->file_name;
	}
	
	/**
	 * @return int
	 */
	public function getOrder(){
		return $this->order;
	}
	
	/**
	 * @return str
	 */
	public function getAuthor(){
		return $this->author;
	}

	/**
	 * @return str
	 */
	public function getLocale(){
		return $this->locale;
	}
	
	/**
	 * valida��o da VO para ver se est� apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->sucess = TRUE;
		if(!Validation::blank($this->name, 2)){
			$ReturnResultVO->sucess = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_IMAGE_VALIDATE_NAME"));
		}
		return $ReturnResultVO;
	}
	
	/*
	 * 	SETAR ERROS NA DAO
	 */
	
	public function resultHandler($tempResult, &$arrayReturn){ //
		switch($tempResult){
			case DbInterface::ERROR_DUPLICATE_ENTRY:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_IMAGE_DUPLICATE_ENTRY");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_IMAGE_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_IMAGE_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_IMAGE_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_IMAGE_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("Arquivo gravado com sucesso");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}

?>