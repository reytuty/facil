<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela comment
	 * @description		: 	
	 * 					A tabela comment
	 *  
			`id;
			`active`, 
			`table`, 
			`table_id`, 
			`user_id`, 
			`title`, 
			`description`, 
			`date`, 
			`order`

	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/comment/dao/CommentDAO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";

class CommentVO extends BaseVO implements PatternVO{
	
	public $id;
	public $active;
	public $table;
	public $table_id;
	public $user_id;
	public $title;
	public $description;
	public $date;
	public $order;
	
	public $status;
  	
  	const TERM_TITLE = "title";
  	const TERM_DESCRIPTION = "description";
  	
	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct();
		$this->__table = "comment";
		//se enviar $arrayFetchPost é para distrinchar intertitlente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia intertitlente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = CommentDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		$this->user_id = DataHandler::getValueByArrayIndex($array_dados, "user_id");
		$this->table = DataHandler::getValueByArrayIndex($array_dados, "table");
		$this->table_id = DataHandler::getValueByArrayIndex($array_dados, "table_id");
		$this->title = DataHandler::getValueByArrayIndex($array_dados, "title");
		$this->description = DataHandler::getValueByArrayIndex($array_dados, "description");
		$this->date = DataHandler::convertDateToDB(DataHandler::getValueByArrayIndex($array_dados, "date")); 
		$this->order = DataHandler::getValueByArrayIndex($array_dados, "order"); 
	}
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->user_id = DataHandler::getValueByStdObjectIndex($obj_dados, "user_id");
		$this->table_id = DataHandler::getValueByStdObjectIndex($obj_dados, "table_id");
		$this->table = DataHandler::getValueByStdObjectIndex($obj_dados, "table");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->title = DataHandler::getValueByStdObjectIndex($obj_dados, "title");
		$this->description = DataHandler::getValueByStdObjectIndex($obj_dados, "description");
		$this->date = DataHandler::convertDateToDB(DataHandler::getValueByStdObjectIndex($obj_dados, "date")); 
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order"); 
	}
	
	//---------------------------------------------------------  SETs

	/**
	 * @param $user_id (int)
	 * @return void
	 */
	public function setUserId($user_id){
		if($user_id !== NULL){
			$this->user_id = DataHandler::forceInt($user_id);
		}
	}
	/**
	 * @param $table_id (int)
	 * @return void
	 */
	public function setTableId($table_id){
		if($table_id !== NULL){
			$this->table_id = DataHandler::forceInt($table_id);
		}
	}
	/**
	 * @param $table (str)
	 * @return void
	 */
	public function setTable($table){
		if($table !== NULL){
			$this->table = DataHandler::forceString($table);
		}
	}
	/**
	 * @param $title (str)
	 * @param $locale
	 * @return void
	 */
	public function setTitle($title, $locale = NULL){
		if($locale == NULL){
			$this->title = DataHandler::forceString($title);
		} else {
			$this->addTerms(CommentVO::TERM_TITLE, $locale, $title);
		}
	}
	/**
	 * @param $description (str)
	 * @param $locale
	 * @return void
	 */
	public function setDescription($description, $locale = NULL){
		if($locale == NULL){
			$this->description = DataHandler::forceString($description);
		} else {
			$this->addTerms(CommentVO::TERM_DESCRIPTION, $locale, $description);
		}
	}
	/**
	 * @param $order (int)
	 * @return void
	 */
	public function setOrder($order){
		if($order !== NULL){
			$this->order = DataHandler::forceInt($order);
		}
	}
	/**
	 * @param $date (date)
	 * @return void
	 */
	public function setDate($date){
		if($date !== NULL){
			$this->date = DataHandler::convertDateToBrazil($date);
		}
	}
	// ------------------------------------------------------   GETs
	/**
	 * @return $user_id (int)
	 */
	public function getUserId(){
		return $this->user_id;
	}
	/**
	 * @return $table_id (int)
	 */
	public function getTableId(){
		return $this->table_id;
	}
	/**
	 * @return $table (str)
	 */
	public function getTable(){
		return $this->table;
	}
	/**
	 * @param $locale
	 * @return $title (str)
	 */
	public function getTitle($locale = NULL){
		if($locale == NULL){
			return $this->title;
		} else {
			$tempResult = $this->getStringTerm(CommentVO::TERM_TITLE, $locale);
			return ($tempResult == "")?$this->title:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return $description (str)
	 */
	public function getDescription($locale = NULL){
		if($locale == NULL){
			return $this->description;
		} else {
			$tempResult = $this->getStringTerm(CommentVO::TERM_DESCRIPTION, $locale);
			return ($tempResult == "")?$this->description:$tempResult;
		}
	}
	/**
	 * @return $order (int)
	 */
	public function getOrder(){
		return $this->order;
	}
	/**
	 * @return $date (date)
	 */
	public function getDate(){
		return DataHandler::convertDateToBrazil($this->date);
	}
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		//if(!$this->getTitle() > 0){
		//	$ReturnResultVO->success = FALSE;
		//	$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_COMMENT_UPDATE_DONOT_HAVE_UPDATE"));
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
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COMMENT_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COMMENT_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COMMENT_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_COMMENT_INSERT_COMMIT");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("LibraryLanguage::SUCCESS_COMMENT");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
}