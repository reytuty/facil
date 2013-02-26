<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: tabela view_product_model_quadro = tabla product_model + inner join de tabela product_model_democrart_quadrado  
	 * 
	 *tabelas com padrao
	 					id,
						active, 
						user_type_id,
						login,
						password,
						email

	 */

	include_once "facil3/core/modules/user/vo/UserVO.class.php";
	include_once "facil3/core/DbInterface.class.php";
	include_once "facil3/interface/dao/PatternDAO.class.php";
	
	class UserDAO extends DbInterface implements PatternDAO{
		
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new UserDAO();
			}
			return self::$instance;
		}

		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
				$VO->active,
			  	$VO->user_type_id,
				$VO->login,
			  	$VO->password,
			  	$VO->email	
			);
		}
		/**
		 * @param $active (tinyint)
		 * @param $user_type_id (int)
		 * @param $login (str)
		 * @param $password (str)
		 * @param $email (int)
		 * @return $ReturnDataVO (ReturnDataVO)
		 */
		public function insert(
	  						$active = 0, 
	  						$user_type_id = NULL,
	  						$login = NULL,
	  						$password = NULL,
	  						$email = NULL
  						){
			
			$query = "INSERT INTO 
						user (
								`id`, 
		  						`active`, 
		  						`user_type_id`, 
		  						`login`, 
		  						`password`, 
		  						`email`
						)VALUES(
								NULL,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceInt($user_type_id)."',
								'".DataHandler::forceString($login)."',
								'".DataHandler::forceString($password)."',
								'".DataHandler::forceString($email)."'
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
			$query = "UPDATE user SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE user SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "DELETE FROM user WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			//echo $query;
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param VO
	     * @return class ReturnDataVO 
	     */
		public function updateVO($VO){
			return $this->update(
						$VO->getId(),
						$VO->active,
					  	$VO->user_type_id,
						$VO->login,
					  	$VO->password,
					  	$VO->email
				);
		}
		/**
		 * @param $id
		 * @param $active
		 * @param $user_type_id
		 * @param $login
		 * @param $password
		 * @param $email
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function update(
							$id,
							$active = NULL, 
	  						$user_type_id = NULL,
	  						$login = NULL,
	  						$password = NULL,
	  						$email = NULL
  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE user SET ";
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
			if($user_type_id > 0){
				$query .= $comma." `user_type_id` = '".DataHandler::forceInt($user_type_id)."'";
				$comma = ", ";
			}
			if($login !== NULL){
				$query .= $comma." `login` = '".DataHandler::forceString($login)."'";
				$comma = ", ";
			}
			if($password !== NULL){
				$query .= $comma." `password` = '".DataHandler::forceString($password)."'";
				$comma = ", ";
			}
			if($email !== NULL){
				$query .= $comma." `email` = '".DataHandler::forceString($email)."'";
				$comma = ", ";
			}
			$query .= "	WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1";
			//echo $query;
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
		 * @param $user_type_id
		 * @param $login
		 * @param $password
		 * @param $email_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL, 
								$active = NULL, 
								$user_type_id = NULL,
								$login = NULL, 
								$password = NULL,
								$email = NULL,						
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
						*
						FROM 
					  	user 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$user_type_id,
								$login,
								$password, 
								$email
							);
			
			$queryCount = "SELECT 
								COUNT(*) AS count
							FROM 
								user
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$user_type_id,
								$login,
								$password, 
								$email
						);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			$ReturnDataVO = parent::query($query);
			
			//executa a query para gerar count total
			$ReturnTotalDataVO = parent::query($queryCount);
			if(FALSE){
				$ReturnDataVO = new ReturnDataVO();
			}
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
						$ReturnDataVO->fetchAll();
						break;
				}
				
				$ReturnTotalDataVO->fetchAll();
				$tempTotal = $ReturnTotalDataVO->result[0]->count;
				$ReturnDataVO->count_total = $tempTotal;
				
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
						
		}
		
		private function addFilter(
								&$query,
								$id, 
								$active, 
								$user_type_id,
								$login,
								$password, 
								$email
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($user_type_id !== NULL){
				$query .= " AND `user_type_id` = '".DataHandler::forceInt($user_type_id)."'";
			}
			if($login !== NULL){
				$query .= " AND `login` = '".DataHandler::forceString($login)."'";
			}
			if($password !== NULL){
				$query .= " AND `password` = '".DataHandler::forceString($password)."'";
			}
			if($email !== NULL){
				$query .= " AND `email` = '".DataHandler::forceString($email)."'";
			}
			//adiciona a ordenação na query
		}
		
		public function getVO(){
			return new UserVO();
		}
}