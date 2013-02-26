<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: user_address
	 * 
	 *tabelas com padrao
	 					id,
						address_id, 
						user_id,

	 */

	include_once "facil3/core/modules/user_address/vo/UserAddressVO.class.php";
	include_once "facil3/core/modules/address/dao/AddressDAO.class.php";
	include_once "facil3/core/DbInterface.class.php";
	include_once "facil3/interface/dao/PatternDAO.class.php";
	
	class UserAddressDAO extends DbInterface implements PatternDAO{
		
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new UserAddressDAO();
			}
			return self::$instance;
		}

		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
				$VO->address_id,
			  	$VO->user_id
		  	);
		}
		/**
		 * @param $address_id (tinyint)
		 * @param $user_id (int)
		 * @return $ReturnDataVO (ReturnDataVO)
		 */
		public function insert(
	  						$address_id = 0, 
	  						$user_id = 0
	  						){
			
			$query = "INSERT INTO 
						user_address (
								`id`, 
		  						`address_id`, 
		  						`user_id`
		  				)VALUES(
								NULL,
								'".DataHandler::forceInt($address_id)."',
								'".DataHandler::forceInt($user_id)."'
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
			$query = "UPDATE user_address SET `id` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE user_address SET `id` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "UPDATE user_address SET `id` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function deleteByUserAndAddress($user_id, $address_id){
			$query = "DELETE FROM user_address WHERE address_id = '".DataHandler::forceInt($address_id)."' AND user_id = '".DataHandler::forceInt($user_id)."' LIMIT 1 ";
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
						$VO->address_id,
					  	$VO->user_id
					  	);
		}
		/**
		 * @param $id
		 * @param $address_id
		 * @param $user_id
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function update(
							$id,
							$address_id = NULL, 
	  						$user_id = NULL
	  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE user_address SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$comma = "";
			if($address_id !== NULL){
				$query .= $comma." `address_id` = '".DataHandler::forceInt($address_id)."'";
				$comma = ", ";
			}
			if($user_id > 0){
				$query .= $comma." `user_id` = '".DataHandler::forceInt($user_id)."'";
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
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function selectByUserId($typeOfReturn = NULL, $id){
			$typeOfReturn = DbInterface::RETURN_STD_OBJECT;
			$query = "SELECT 
							address.*,
							state.name as state_name,
							country.name as country_name
						FROM
							user_address
								INNER JOIN
									address 
										ON address.id = user_address.address_id
								LEFT JOIN
									state
										ON address.state_id = state.id 
								LEFT JOIN
									country
										ON country.id = state.country_id 
						WHERE user_address.user_id = ".$id;
			
			$AddressDAO = new AddressDAO;
			
			//executa a query
			$ReturnDataVO = $AddressDAO->query($query);
			//echo $query;
			if($ReturnDataVO->success){
				switch($typeOfReturn){
					case DbInterface::RETURN_ARRAY:
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_ARRAY);
						break;
					case DbInterface::RETURN_VO:
						$ReturnDataVO->fetchAllVO($ReturnDataVO);
						break;
					case DbInterface::RETURN_STD_OBJECT:
					default:
						//retornar tudo em objeto
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_OBJECT);
						break;
				}
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
		}			
		

		/**
		 * @param $typeOfReturn
		 * @param $id
		 * @param $address_id
		 * @param $user_id
		 * @param $number_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL, 
								$address_id = NULL, 
								$user_id = NULL,
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
						*
						FROM 
					  	user_address 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$address_id, 
								$user_id
							);
			
			$queryCount = "Select 
				     			user_address.*,
								COUNT(*) AS count
							FROM 
								user_address
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$address_id, 
								$user_id
						);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//echo $query;
			//executa a query
			$ReturnDataVO = parent::query($query);
			//executa a query para gerar count total
			$ReturnTotalDataVO = parent::query($queryCount);
			
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
								$address_id, 
								$user_id
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($address_id !== NULL){
				$query .= " AND `address_id` = '".DataHandler::forceInt($address_id)."'";
			}
			if($user_id !== NULL){
				$query .= " AND `user_id` = '".DataHandler::forceInt($user_id)."'";
			}
			//adiciona a ordenação na query
		}
		
		public function getVO(){
			return new UserAddressVO();
		}
}