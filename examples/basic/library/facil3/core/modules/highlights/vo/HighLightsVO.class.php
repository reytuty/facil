<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: highlights
	 * @content		: 	
	 * 
	 					id,
						active, 
						name,
						link,
						content,
						image_url,
						date,
						date_in,
						date_out,
						order

	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/highlights/dao/HighLightsDAO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";

class HighLightsVO extends BaseVO implements PatternVO{
	
 	 public $name;
	 public $link;
	 public $content;
	 public $image_url;
	 public $date;
	 public $date_in;
	 public $date_out;
	 public $order;
  	
  	
  	const TERM_NAME 			= "name";
  	const TERM_LINK 			= "link";
  	const TERM_CONTENT 			= "content";
  	const TERM_IMAGE_URL		= "image_url";
  	
  	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct();
		$this->__table = "highlights";
		//se enviar $arrayFetchPost é para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = HighLightsDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		$this->name = DataHandler::getValueByArrayIndex($array_dados, "name");
		$this->link = DataHandler::getValueByArrayIndex($array_dados, "link");
		$this->content = DataHandler::getValueByArrayIndex($array_dados, "content");
		$this->image_url = DataHandler::getValueByArrayIndex($array_dados, "image_url");
		$this->date = DataHandler::getValueByArrayIndex($array_dados, "date"); 
		$this->date_in = DataHandler::getValueByArrayIndex($array_dados, "date_in"); 
		$this->date_out = DataHandler::getValueByArrayIndex($array_dados, "date_out"); 
		$this->order = DataHandler::getValueByArrayIndex($array_dados, "order"); 
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->name = DataHandler::getValueByStdObjectIndex($obj_dados, "name");
		$this->link = DataHandler::getValueByStdObjectIndex($obj_dados, "link");
		$this->content = DataHandler::getValueByStdObjectIndex($obj_dados, "content");
		$this->image_url = DataHandler::getValueByStdObjectIndex($obj_dados, "image_url");
		$this->date = DataHandler::getValueByStdObjectIndex($obj_dados, "date"); 
		$this->date_in = DataHandler::getValueByStdObjectIndex($obj_dados, "date_in"); 
		$this->date_out = DataHandler::getValueByStdObjectIndex($obj_dados, "date_out"); 
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order"); 
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
			$this->addTherms(HighLightsVO::TERM_NAME, $locale, $name);
		}
	}
	/**
	 * @param $link
	 * @param $locale
	 * @return void
	 */
	public function setLink($link, $locale = NULL){
		if($locale == NULL){
			$this->link = DataHandler::forceString($link);
		} else {
			$this->addTherms(HighLightsVO::TERM_LINK, $locale, $link);
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
			$this->addTherms(HighLightsVO::TERM_CONTENT, $locale, $content);
		}
	}
	/**
	 * @param $image_url
	 * @param $locale
	 * @return void
	 */
	public function setImageUrl($image_url, $locale = NULL){
		if($locale == NULL){
			$this->image_url = DataHandler::removeSpecialCharacters($image_url);
		} else {
			$this->addTherms(HighLightsVO::TERM_IMAGE_URL, $locale, DataHandler::removeSpecialCharacters($image_url));
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
			$this->date = DataHandler::convertDateToDB($date);
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
	
	// ------------------------------------------------------   GETs
	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale == NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(HighLightsVO::TERM_NAME, $locale);
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getLink($locale = NULL){
		if($locale == NULL){
			return $this->link;
		} else {
			$tempResult = $this->getStringTherm(HighLightsVO::TERM_LINK, $locale);
			return ($tempResult == "")?$this->link:$tempResult;
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
			$tempResult = $this->getStringTherm(HighLightsVO::TERM_CONTENT, $locale);
			return ($tempResult == "")?$this->content:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getImageUrl($locale = NULL){
		if($locale == NULL){
			return $this->image_url;
		} else {
			$tempResult = $this->getStringTherm(HighLightsVO::TERM_IMAGE_URL, $locale);
			return ($tempResult == "")?$this->image_url:$tempResult;
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
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_HIGHLIGHTS_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_HIGHLIGHTS");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
	
}