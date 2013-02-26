<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: content
	 * @description		: 	
	 * 					content 
	 					id,
						active, 
						name,
						title,
						description,
						content,
						author,
						template_url,
						slug,
						key_words,
						order
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/content/dao/ContentDAO.class.php";
include_once "library/facil3/core/modules/comment/vo/BaseCommentVO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";

class ContentVO extends BaseCommentVO implements PatternVO{
	
 	 public $name;
	 public $title;
	 public $hat;
	 public $description;
	 public $content;
	 public $author;
	 public $template_url;
	 public $slug;
	 public $key_words;
	 public $date;
	 public $date_in;
	 public $date_out;
	 public $order;
  	
  	public $array_content_childs;
  	
  	const TERM_NAME 			= "name";
  	const TERM_TITLE 			= "title";
  	const TERM_HAT	 			= "hat";
  	const TERM_DESCRIPTION 		= "description";
  	const TERM_CONTENT 			= "content";
  	const TERM_AUTHOR 			= "author";
  	const TERM_SLUG 			= "slug";
  	const TERM_KEY_WORDS 		= "key_words";
  	const TERM_TEMPLATE_URL		= "template_url";
	const TERM_IMAGE_URL		= "image_url";
	const TERM_LINK				= "link";
  	
  	function __construct($arrayFetchPost = NULL, $locale = NULL){
		//inicia funo de validacao
		parent::_construct();
		$this->__table = "content";
		//se enviar $arrayFetchPost é para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost, $locale);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = ContentDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados, $locale = NULL){
		if(DataHandler::getValueByArrayIndex($array_dados, "id")){
			$this->setId(DataHandler::getValueByArrayIndex($array_dados, "id"));
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "active")){
			$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "name")){
			$this->setName(DataHandler::getValueByArrayIndex($array_dados, "name")					, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "title")){
			$this->setTitle(DataHandler::getValueByArrayIndex($array_dados, "title")				, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "hat")){
			$this->setHat(DataHandler::getValueByArrayIndex($array_dados, "hat")					, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "description")){
			$this->setDescription(DataHandler::getValueByArrayIndex($array_dados, "description")	, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "content")){
			$this->setContent(DataHandler::getValueByArrayIndex($array_dados, "content")			, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "author")){
			$this->setAuthor(DataHandler::getValueByArrayIndex($array_dados, "author")				, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "template_url")){
			$this->setTemplateUrl(DataHandler::getValueByArrayIndex($array_dados, "template_url")	, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "slug")){
			$this->setSlug(DataHandler::getValueByArrayIndex($array_dados, "slug")					, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "key_words")){
			$this->setKeyWords(DataHandler::getValueByArrayIndex($array_dados, "key_words")			, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "date")){
			$this->setDate(DataHandler::getValueByArrayIndex($array_dados, "date"));
		} 
		if(DataHandler::getValueByArrayIndex($array_dados, "date_id")){
			$this->date_in = DataHandler::getValueByArrayIndex($array_dados, "date_in");
		} 
		if(DataHandler::getValueByArrayIndex($array_dados, "date_out")){
			$this->date_out = DataHandler::getValueByArrayIndex($array_dados, "date_out");
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "order")){
			$this->order = DataHandler::getValueByArrayIndex($array_dados, "order");
		} 
		if(DataHandler::getValueByArrayIndex($array_dados, "image_url")){
			$this->setImageURL(DataHandler::getValueByArrayIndex($array_dados, "image_url")			, $locale);
		}
		if(DataHandler::getValueByArrayIndex($array_dados, "link")){
			$this->setLink(DataHandler::getValueByArrayIndex($array_dados, "link")					, $locale);
		}
	}
	
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->name = DataHandler::getValueByStdObjectIndex($obj_dados, "name");
		$this->title = DataHandler::getValueByStdObjectIndex($obj_dados, "title");
		$this->hat = DataHandler::getValueByStdObjectIndex($obj_dados, "hat");
		$this->description = DataHandler::getValueByStdObjectIndex($obj_dados, "description");
		$this->content = DataHandler::getValueByStdObjectIndex($obj_dados, "content");
		$this->author = DataHandler::getValueByStdObjectIndex($obj_dados, "author");
		$this->template_url = DataHandler::getValueByStdObjectIndex($obj_dados, "template_url");
		$this->slug = DataHandler::getValueByStdObjectIndex($obj_dados, "slug");
		$this->key_words = DataHandler::getValueByStdObjectIndex($obj_dados, "key_words");
		$this->date = DataHandler::getValueByStdObjectIndex($obj_dados, "date"); 
		$this->date_in = DataHandler::getValueByStdObjectIndex($obj_dados, "date_in"); 
		$this->date_out = DataHandler::getValueByStdObjectIndex($obj_dados, "date_out"); 
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order");
		
		$this->setImageURL(DataHandler::getValueByStdObjectIndex($obj_dados, "image_url"), DataHandler::getValueByStdObjectIndex($obj_dados, "locale"));
		$this->setLink(DataHandler::getValueByStdObjectIndex($obj_dados, "link"), DataHandler::getValueByStdObjectIndex($obj_dados, "locale"));
	}
	

	//---------------------------------------------------------  SETs
	
	/**
	 * @param int active
	 */
	//public function setActive($active){
	//	if($active !== NULL){
	//		$this->active = DataHandler::forceInt($active);
	//	}
	//}
	/**
	 * @param $name
	 * @param $locale
	 * @return void
	 */
	public function setName($name, $locale = NULL){
		if($locale == NULL){
			$this->name = DataHandler::forceString($name);
		} else {
			$this->addTherms(ContentVO::TERM_NAME, $locale, $name);
		}
	}
	/**
	 * @param $hat
	 * @param $locale
	 * @return void
	 */
	public function setHat($hat, $locale = NULL){
		if($locale == NULL){
			$this->hat = DataHandler::forceString($hat);
		} else {
			$this->addTherms(ContentVO::TERM_HAT, $locale, $hat);
		}
	}
	/**
	 * @param $title
	 * @param $locale
	 * @return void
	 */
	public function setTitle($title, $locale = NULL){
		if($locale == NULL){
			$this->title = DataHandler::forceString($title);
		} else {
			$this->addTherms(ContentVO::TERM_TITLE, $locale, $title);
		}
	}
	/**
	 * @param $description
	 * @param $locale
	 * @return void
	 */
	public function setDescription($description, $locale = NULL){
		if($locale == NULL){
			$this->description = DataHandler::forceString($description);
		} else {
			$this->addTherms(ContentVO::TERM_DESCRIPTION, $locale, $description);
		}
	}
	/**
	 * @param $content
	 * @param $locale
	 * @return void
	 */
	public function setContent($content, $locale = NULL){
		if($locale == NULL){
			$this->content = DataHandler::forceString($content);
		} else {
			$this->addTherms(ContentVO::TERM_CONTENT, $locale, $content);
		}
	}
	/**
	 * @param $author
	 * @param $locale
	 * @return void
	 */
	public function setAuthor($author, $locale = NULL){
		if($locale == NULL){
			$this->author = DataHandler::forceString($author);
		} else {
			$this->addTherms(ContentVO::TERM_AUTHOR, $locale, $author);
		}
	}
	/**
	 * @param $template_url
	 * @param $locale
	 * @return void
	 */
	public function setTemplateUrl($template_url, $locale = NULL){
		if($locale == NULL){
			$this->template_url = $template_url;
		} else {
			$this->addTherms(ContentVO::TERM_TEMPLATE_URL, $locale, DataHandler::removeSpecialCharacters($template_url));
		}
	}
	/**
	 * @param $slug
	 * @param $locale
	 * @return void
	 */
	public function setSlug($slug, $locale = NULL){
		if($locale == NULL){
			$this->slug = DataHandler::removeSpecialCharacters($slug);
		} else {
			$this->addTherms(ContentVO::TERM_SLUG, $locale, DataHandler::removeSpecialCharacters($slug));
		}
	}
	/**
	 * @param $key_words
	 * @param $locale
	 * @return void
	 */
	public function setKeyWords($key_words, $locale = NULL){
		if($locale == NULL){
			$this->key_words = DataHandler::cleanStringsForSearch($key_words);
		} else {
			$this->addTherms(ContentVO::TERM_KEY_WORDS, $locale, DataHandler::cleanStringsForSearch($key_words));
		}
	}
	/**
	 * @param int order
	 */
	public function setOrder($order){
		if($order !== NULL){
			$this->order = DataHandler::forceInt($order);
		}
	}

	/**
	 * @param date (datetime)
	 */
	public function setDate($date){
		if($date !== NULL){
			$this->date = ($date=="now()")?"now()":DataHandler::convertDateToDB($date);
		}
	}
	
	/**
	 * @param date_in (datetime)
	 */
	public function setDateIn($date_in){
		if($date_in !== NULL){
			$this->date_in = DataHandler::convertDateToDB($date_in);
		}
	}
	
	/**
	 * @param date_out (datetime)
	 */
	public function setDateOut($date_out){
		if($date_out !== NULL){
			$this->date_out = DataHandler::convertDateToDB($date_out);
		}
	}
	
	
	
	/**
	 * @param (string)$image_url 
	 * @param (string)$locale 
	 */
	public function setImageURL($image_url, $locale = NULL){
		if($locale) 
			$this->addTherms(ContentVO::TERM_IMAGE_URL, $locale, DataHandler::forceString($image_url));
	}
	
	/**
	 * @param (string)$image_url 
	 * @param (string)$locale 
	 */
	public function setLink($link, $locale = NULL){
		if($locale) 
			$this->addTherms(ContentVO::TERM_LINK, $locale, DataHandler::forceString($link));
	}
	
	
	// ------------------------------------------------------   GETs
	
	
	/**
	 * @param $locale
	 * @return (string)$image_url 
	 */
	public function getImageUrl( $locale = NULL){
		if($locale)
			$this->getStringTherm(ContentVO::TERM_IMAGE_URL, $locale);
	}
	
	/**
	 * @param $locale
	 * @return (string)$image_url 
	 */
	public function getLink( $locale = NULL){
		if($locale)
			$this->getStringTherm(ContentVO::TERM_LINK, $locale);
	}
	
	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale == NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_NAME, $locale);
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getHat($locale = NULL){
		if($locale == NULL){
			return $this->hat;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_HAT, $locale);
			return ($tempResult == "")?$this->hat:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getTitle($locale = NULL){
		if($locale == NULL){
			return $this->title;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_TITLE, $locale);
			return ($tempResult == "")?$this->title:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getDescription($locale = NULL){
		if($locale == NULL){
			return $this->description;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_DESCRIPTION, $locale);
			return ($tempResult == "")?$this->description:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getContent($locale = NULL){
		if($locale == NULL){
			return $this->content;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_CONTENT, $locale);
			return ($tempResult == "")?$this->content:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return int $author
	 */
	public function getAuthor($locale = NULL){
		if($locale == NULL){
			return $this->author;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_AUTHOR, $locale);
			return ($tempResult == "")?$this->author:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getTemplateUrl($locale = NULL){
		if($locale == NULL){
			return $this->template_url;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_TEMPLATE_URL, $locale);
			return ($tempResult == "")?$this->template_url:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getSlug($locale = NULL){
		if($locale == NULL){
			return $this->slug;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_SLUG, $locale);
			return ($tempResult == "")?$this->slug:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getKeyWords($locale = NULL){
		if($locale == NULL){
			return $this->key_words;
		} else {
			$tempResult = $this->getStringTherm(ContentVO::TERM_KEY_WORDS, $locale);
			return ($tempResult == "")?$this->key_words:$tempResult;
		}
	}
	
	/**
	 * @return int $order
	 */
	public function getOrder(){
		return $this->order;
	}

	/**
	 * @param $locale
	 * @return date (datetime)
	 */
	public function getDate($locale = NULL){
		return DataHandler::convertDbDateToLocale($locale, $this->date);
	}

	/**
	 * @param $locale
	 * @return date_in (datetime)
	 */
	public function getDateIn($locale = NULL){
		return DataHandler::convertDbDateToLocale($locale, $this->date_in);
	}

	/**
	 * @param $locale
	 * @return date_out (datetime)
	 */
	public function getDateOut($locale = NULL){
		return DataHandler::convertDbDateToLocale($locale, $this->date_out);
	}
	
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		///if(!$this->getName() > 0){
		//	$ReturnResultVO->success = FALSE;
		//	$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_MENU_UPDATE_DONOT_HAVE_UPDATE"));
		//}
		return $ReturnResultVO;
	}
	
	/**
	 * @param int $tempResult
	 * @param array $arrayReturn
	 */
	public function resultHandler($tempResult, &$arrayReturn){
		switch($tempResult){
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CONTENT_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CONTENT_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CONTENT_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CONTENT_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_CONTENT");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
	
}