<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: tabela comment  
	 * 
	 *tabelas com padrao
			`id`, 
			`active`, 
			`table`, 
			`table_id`, 
			`user_id`, 
			`title`, 
			`description`, 
			`date`, 
			`order`
	 */

	include_once "library/facil3/core/modules/comment/vo/CommentVO.class.php";
	include_once "library/facil3/core/modules/comment/vo/ItemCommentVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	
	class CommentDAO extends DbInterface implements PatternDAO{
		
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new CommentDAO();
			}
			return self::$instance;
		}
		
		const success 			= 1;
		
		const STATUS_OK 		= "ok";
		const STATUS_INSERT		= "insert";
		const STATUS_UPDATE		= "update";
		const STATUS_DELETE		= "delete";
		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
				$VO->active,
				$VO->table,
				$VO->table_id,
				$VO->user_id,
				$VO->title,
				$VO->description,
				$VO->date,
				$VO->order
			);
		}

		/**
		 * @param $active
		 * @param $table
		 * @param $table_id
		 * @param $user_id
		 * @param $title
		 * @param $description
		 * @param $date
		 * @param $order
		 * @return ReturnDataVO
		 */
		public function insert(
					$active,
					$table,
					$table_id,
					$user_id,
					$title,
					$description,
					$date,
					$order
				){

			$query = "INSERT INTO 
						comment (
							`id`, 
							`active`, 
							`table`, 
							`table_id`, 
							`user_id`, 
							`title`, 
							`description`, 
							`date`, 
							`order`
						)VALUES(
								NULL,
								'".DataHandler::forceInt($active)."', 
								'".DataHandler::forceString($table)."', 
								'".DataHandler::forceInt($table_id)."', 
								'".DataHandler::forceInt($user_id)."', 
								'".DataHandler::forceString($title)."',
								'".DataHandler::forceString($description)."', 
								NOW(),
								'".DataHandler::forceInt($order)."'
						)";
			$ReturnDataVO = parent::query($query, TRUE);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function active($id){
			$query = "UPDATE comment SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE comment SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "UPDATE comment SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}

		/* (non-PHPdoc)
		 * @see classes/interface/dao/PatternDAO#updateVO($VO)
		 */
		public function updateItemVO($BaseVO){
			//Debug::li("CommentDAO -> updateVO chamado.");
			//aqui recebe o CommentVO
			$arrayComment = $BaseVO->getComments();
			//Debug::print_r($arrayComment);
			//iniciando array de erros fora do for
			$erros = array();
			for($i = 0; $i < count($arrayComment); $i++){
				//varrendo array de comments
					$tempVO = $arrayComment[$i];
					//exit();
					if($tempVO->getStatus() == CommentDAO::STATUS_OK){
						//Debug::li("o commentpo [$i] j est ok ");
						continue;
					} else {
						if($tempVO->getStatus() == CommentDAO::STATUS_UPDATE){
							//Debug::li("CommentDAO . updateVO -> vai alterar essa CommentVO");
							//alterar uma entrada j existente - no altera seo
							$tempVO->setStatus(CommentDAO::STATUS_OK);
							$ReturnDataVO = $this->update($tempVO->getId(), $tempVO->active, $tempVO->table, $tempVO->table_id, $tempVO->user_id, $tempVO->title, $tempVO->description, $tempVO->date, $tempVO->order);
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_COMMENT_UPDATE_COMMIT;
							}
							//Debug::print_r($BaseVO);
							//exit();
							//Debug::li("erro na CommentDAO - > ".Debug::print_r($erros));
							//return $ReturnDataVO;
						} else if($tempVO->getStatus() == CommentDAO::STATUS_INSERT){
							//Debug::li("CommentDAO . updateVO -> vai inserir essa CommentVO");
							//incluir nova entrada
							$tempVO->setStatus(CommentDAO::STATUS_OK);
							$ReturnDataVO = $this->insert($tempVO->active, $BaseVO->__table, $BaseVO->getId(), $tempVO->user_id,	$tempVO->title, $tempVO->description, $tempVO->date, $tempVO->order);
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_COMMENT_INSERT_COMMIT;
							}
							//var_dump($ReturnDataVO);
						} else if($tempVO->getStatus() == CommentDAO::STATUS_DELETE){
							//incluir nova entrada
							//Debug::li("CommentDAO . updateVO -> vai DELETAR essa CommentVO");
							$ReturnDataVO = $this->delete($tempVO->getId());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_COMMENT_INSERT_COMMIT;
							} else {
								$tempVO->setStatus(CommentDAO::STATUS_OK);
							}
							//var_dump($temp);
						}
				}//end for $j
			}
			$ReturnDataVO = new ReturnDataVO();
			if(count($erros) <= 0){
				$ReturnDataVO->success = TRUE;
				//Debug::li("TUDO CERTO NA CommentDAO ");
			} else {
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = $erros;
				//Debug::li("erro na CommentDAO - > ".Debug::print_r($erros));
			}
			return $ReturnDataVO;
		}//end function

		public function updateVO($VO){
			return $this->update( 
				$VO->id,
				$VO->active,
				$VO->table,
				$VO->table_id,
				$VO->user_id,
				$VO->title,
				$VO->description,
				$VO->date,
				$VO->order
			);
		}
				/**
		 * @param $id
		 * @param $active
		 * @param $table
		 * @param $table_id
		 * @param $user_id
		 * @param $title
		 * @param $description
		 * @param $date
		 * @param $order
		 * @return ReturnDataVO
		 */
		public function update(
							$id = NULL,
							$active = NULL,
							$table = NULL,
							$table_id = NULL,
							$user_id = NULL,
							$title = NULL,
							$description = NULL,
							$date = NULL,
							$order = NULL
  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE comment SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$comma = "";
			if($active !== NULL){
				$query .= $comma." `active` = '".DataHandler::forceInt($active)."'";
				$comma = ", ";
			}
			if($table > 0){
				$query .= $comma." `table` = '".DataHandler::forceString($table)."'";
				$comma = ", ";
			}
			if($user_id > 0){
				$query .= $comma." `user_id` = '".DataHandler::forceInt($user_id)."'";
				$comma = ", ";
			}
			if($table_id > 0){
				$query .= $comma." `table_id` = '".DataHandler::forceInt($table_id)."'";
				$comma = ", ";
			}
			if($title !== NULL){
				$query .= $comma." `title` = '".DataHandler::forceString($title)."'";
				$comma = ", ";
			}
			if($description !== NULL){
				$query .= $comma." `description` = '".DataHandler::forceString($description)."'";
				$comma = ", ";
			}
			if($date !== NULL){
				$query .= $comma." `date` = '".DataHandler::convertDateToDB($date)."'";
				$comma = ", ";
			}
			if($order !== NULL){
				$query .= $comma." `order` = '".DataHandler::forceInt($order)."'";
				$comma = ", ";
			}
			$query .= "	WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1";
			if($comma == ""){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES;
				//Debug::print_r($ReturnDataVO);
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				//Debug::print_r($ReturnDataVO);
				return $ReturnDataVO;
			}
		}
		
		
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function selectById($id){
			$ReturnDataVO = $this->select(DbInterface::RETURN_STD_OBJECT, $id);
			return $ReturnDataVO;
		}
		
		/**
		 * @param $typeOfReturn
		 * @param $id
		 * @param $active
		 * @param $uid
		 * @param $name
		 * @param $description
		 * @param $order_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function select(
							$typeOfReturn = NULL,
							$id = NULL,
							$active = NULL,
							$table = NULL,
							$table_id = NULL,
							$user_id = NULL,
							$title = NULL,
							$description = NULL,
							$date = NULL,
							$symble_date = ">=",
							$order_type = NULL,
							$quant_started = NULL,
							$quant_limit = NULL
							
						){

			$arrayFilter = array();
			$query = "SELECT 
						*
						FROM 
					  	comment 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id,
								$active,
								$table,
								$table_id,
								$user_id,
								$title,
								$description,
								$date,
								$symble_date,
								$order_type
							);
			
			$queryCount = "Select 
				     			comment.*,
								COUNT(*) AS count
							FROM 
								comment
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id,
								$active,
								$table,
								$table_id,
								$user_id,
								$title,
								$description,
								$date,
								$symble_date,
								$order_type
						);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			$ReturnDataVO = parent::query($query);
			//executa a query para gerar count total
			$ReturnTotalDataVO = parent::query($queryCount);
			//echo $query;
			if($ReturnDataVO->success){
				switch($typeOfReturn){
					case DbInterface::RETURN_ARRAY:
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_ARRAY);
						break;
					case DbInterface::RETURN_VO:
						$ReturnDataVO->fetchAllVO($this);
						break;
					case DbInterface::RETURN_STD_OBJECT:
					default:
						//retornar tudo em objeto
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_OBJECT);
						break;
				}
				$ReturnTotalDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_OBJECT);
				$tempTotal = $ReturnTotalDataVO->result[0]->count;
				$ReturnDataVO->count_total = $tempTotal;
				
				//echo Debug::print_r($ReturnDataVO);
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
						
		}
		
		private function addFilter(
								&$query,
								$id,
								$active,
								$table,
								$table_id,
								$user_id,
								$title,
								$description,
								$date,
								$symble_date,
								$order_type
							){

			if($id > 0){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($table !== NULL){
				$query .= " AND `table` = '".DataHandler::forceString($table)."'";
			}
			if($table_id > 0){
				$query .= " AND `table_id` = '".DataHandler::forceInt($table_id)."'";
			}
			if($user_id > 0){
				$query .= " AND `user_id` = '".DataHandler::forceInt($user_id)."'";
			}
			if($title !== NULL){
				$query .= " AND `title` = '".DataHandler::forceString($title)."'";
			}
			if($description !== NULL){
				$query .= " AND `description` = '".DataHandler::forceString($description)."'";
			}
			if($date !== NULL){
				$symble_date = parent::compareSimble($symble_date);
				$date = parent::dateHandlerScape($date);
				$query .= " AND `date` $symble_date $date ";
			}
			//adiciona a ordenação na query
			$orderBy = "";
			if($order_type != NULL){
				$order_type = parent::verifyOrderType($order_type);
				$orderBy .= ", `order` ".$order_type;
			}
			if($orderBy != NULL){
				$query .= " ORDER BY ".$orderBy;
			}
			$query .= "ORDER BY date DESC";
		}
		
		public function getVO(){
			return new CommentVO();
		}
		
		public function getItemVO(){
			return new ItemCommentVO();
		}
	}