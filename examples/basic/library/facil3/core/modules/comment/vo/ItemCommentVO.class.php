<?php
/*
 * @autor		: Mauricio Amorim - eu@mauricioamorim.com.br
 * @data		: 13/07/2010
 * @versao		: 1.0
 * @comentario	: Classe fora do padr�o para trabalhar com os values.
 */
/**
	public $id;
	public $active;
	public $table;
	public $table_id;
	public $user_id;
	public $title;
	public $description;
	public $date;
	public $order;
		
*/
class ItemCommentVO{
	public $id;//id do commentario
	public $active;
	public $table;
	public $table_id;
	public $user_id;
	public $title;
	public $description;
	public $date;
	public $order;
		
	public $status;
	
	function __construct($obj_dados = NULL){
		if($obj_dados){
			$this->setFetchObject($obj_dados);			
		}
	}
	/**
	 * @param $id (int)
	 * @return void
	 */
	public function setId($id){
		if($id !== NULL){
			$this->id = DataHandler::forceInt($id);
		}
	}
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
			$this->addComments(CommentVO::TERM_TITLE, $locale, $title);
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
			$this->addComments(CommentVO::TERM_DESCRIPTION, $locale, $description);
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
	 * @param $active (int)
	 * @return void
	 */
	public function setActive($active){
		if($active !== NULL){
			$this->active = DataHandler::forceInt($active);
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
	public function setStatus($str){
		switch($str){
			case CommentDAO::STATUS_OK:
				$type = CommentDAO::STATUS_OK;
				break;
			case CommentDAO::STATUS_DELETE:
				$type = CommentDAO::STATUS_DELETE;
				break;
			case CommentDAO::STATUS_UPDATE:
				$type = CommentDAO::STATUS_UPDATE;
				break;
			case CommentDAO::STATUS_INSERT:
				default:
				$type = CommentDAO::STATUS_INSERT;
				break;
		}
		$this->status = $type;
	}

	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");//pega o id tb
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->table = DataHandler::getValueByStdObjectIndex($obj_dados, "table");
		$this->table_id = DataHandler::getValueByStdObjectIndex($obj_dados, "table_id");
		$this->user_id = DataHandler::getValueByStdObjectIndex($obj_dados, "user_id");
		$this->title = DataHandler::getValueByStdObjectIndex($obj_dados, "title");
		$this->description = DataHandler::getValueByStdObjectIndex($obj_dados, "description");
		$this->date = DataHandler::getValueByStdObjectIndex($obj_dados, "date");
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order");
		$this->setStatus(CommentDAO::STATUS_OK);
	}
	//---------------------[GETs]
	/**
	 * @return $user_id (int)
	 */
	public function getId(){
		return $this->id;
	}	
	
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
	 * @return $active (int)
	 */
	public function getActive(){
		return $this->active;
	}
	/**
	 * @return $date (date)
	 */
	public function getDate(){
		return DataHandler::convertDateToBrazil($this->date);
	}
		
	public function getStatus(){
		return $this->status;
	}
	
}
?>