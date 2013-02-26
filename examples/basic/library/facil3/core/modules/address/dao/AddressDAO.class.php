<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: address  
	 * 
	 *tabelas com padrao
	 					id,
						active, 
						state_id,
						state,
						country,
						alias,
						street,
						number,
						complement,
						neighborhood,
						zip_code,
						city,
						order

	 */

	include_once "facil3/core/modules/address/vo/AddressVO.class.php";
	include_once "facil3/core/DbInterface.class.php";
	include_once "facil3/interface/dao/PatternDAO.class.php";
	
	class AddressDAO extends DbInterface implements PatternDAO{
		
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new AddressDAO();
			}
			return self::$instance;
		}

		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
				$VO->active,
			  	$VO->state_id,
			  	$VO->state,
			  	$VO->country,
				$VO->alias,
			  	$VO->street,
			  	$VO->number,	
				$VO->complement,
				$VO->neighborhood,
				$VO->zip_code,
				$VO->city,
				$VO->order
		  	);
		}
		/**
		 * @param $active
		 * @param $state_id
		 * @param $state
		 * @param $country
		 * @param $alias
		 * @param $street
		 * @param $number
		 * @param $complement
		 * @param $neighborhood
		 * @param $zip_code
		 * @param $city
		 * @param $order
		 * @return unknown_type
		 */
		public function insert(
	  						$active = 1, 
	  						$state_id = NULL,
	  						$state = NULL,
	  						$country = NULL,
	  						$alias = NULL,
	  						$street = NULL,
	  						$number = NULL,
							$complement = NULL,
							$neighborhood = NULL,
							$zip_code = NULL,
							$city = NULL,
							$order = NULL
	  						){
			
			$query = "INSERT INTO 
						address (
								address.`id`, 
		  						address.`active`, 
		  						address.`state_id`, 
		  						address.`state`, 
		  						address.`country`, 
		  						address.`alias`, 
		  						address.`street`, 
		  						address.`number`,
								address.`complement`,
								address.`neighborhood`,
								address.`zip_code`,
								address.`city`,
								address.`order`
		  				)VALUES(
								NULL,
								'1',
								'".DataHandler::forceInt($state_id)."',
								'".DataHandler::forceString($state)."',
								'".DataHandler::forceString($country)."',
								'".DataHandler::forceString($alias)."',
								'".DataHandler::forceString($street)."',
								'".DataHandler::forceString($number)."',
								'".DataHandler::forceString($complement)."',
								'".DataHandler::forceString($neighborhood)."',
								'".DataHandler::forceString($zip_code)."',
								'".DataHandler::forceString($city)."',
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
			$query = "UPDATE address SET address.`active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE address SET address.`active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "DELETE FROM address WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
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
					  	$VO->state_id,
					  	$VO->state,
					  	$VO->country,
					  	$VO->alias,
					  	$VO->street,
					  	$VO->number,
						$VO->complement,
						$VO->neighborhood,
						$VO->zip_code,
						$VO->city,
						$VO->order
					  	);
		}
		
		/**
		 * @param $id
		 * @param $active
		 * @param $state_id
		 * @param $state
		 * @param $country
		 * @param $alias
		 * @param $street
		 * @param $number
		 * @param $complement
		 * @param $neighborhood
		 * @param $zip_code
		 * @param $city
		 * @param $order
		 * @return unknown_type
		 */
		public function update(
							$id,
							$active = NULL, 
	  						$state_id = NULL,
	  						$state = NULL,
	  						$country = NULL,
	  						$alias = NULL,
	  						$street = NULL,
	  						$number = NULL,
							$complement = NULL,
							$neighborhood = NULL,
							$zip_code = NULL,
							$city = NULL,
							$order = NULL
	  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE address SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$comma = "";
			if($active !== NULL){
				$query .= $comma." address.`active` = '".DataHandler::forceInt($active)."'";
				$comma = ", ";
			}
			if($state_id > 0){
				$query .= $comma." address.`state_id` = '".DataHandler::forceInt($state_id)."'";
				$comma = ", ";
			}
			if($state !== NULL){
				$query .= $comma." address.`state` = '".DataHandler::forceString($state)."'";
				$comma = ", ";
			}
			if($country !== NULL){
				$query .= $comma." address.`country` = '".DataHandler::forceString($country)."'";
				$comma = ", ";
			}
			if($alias !== NULL){
				$query .= $comma." address.`alias` = '".DataHandler::forceString($alias)."'";
				$comma = ", ";
			}
			if($street !== NULL){
				$query .= $comma." address.`street` = '".DataHandler::forceString($street)."'";
				$comma = ", ";
			}
			if($number !== NULL){
				$query .= $comma." address.`number` = '".DataHandler::forceString($number)."'";
				$comma = ", ";
			}
			if($complement !== NULL){
				$query .= $comma." address.`complement` = '".DataHandler::forceString($complement)."'";
				$comma = ", ";
			}
			if($neighborhood !== NULL){
				$query .= $comma." address.`neighborhood` = '".DataHandler::forceString($neighborhood)."'";
				$comma = ", ";
			}
			if($zip_code !== NULL){
				$query .= $comma." address.`zip_code` = '".DataHandler::forceString($zip_code)."'";
				$comma = ", ";
			}
			if($city !== NULL){
				$query .= $comma." address.`city` = '".DataHandler::forceString($city)."'";
				$comma = ", ";
			}
			if($order !== NULL){
				$query .= $comma." address.`order` = '".DataHandler::forceString($order)."'";
				$comma = ", ";
			}
			$query .= "	WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1";
			//echo "query";
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
		 * @param $state_id
		 * @param $state
		 * @param $country
		 * @param $alias
		 * @param $street
		 * @param $number
		 * @param $quant_limit
		 * @param $quant_started
		 * @return unknown_type
		 */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL, 
								$active = NULL, 
								$state_id = NULL,
								$state = NULL,
								$country = NULL,
								$alias = NULL, 
								$street = NULL,
								$number = NULL,						
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
						*
						FROM 
					  	address 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$state_id,
								$state,
								$country,
								$alias,
								$street, 
								$number
							);
			
			$queryCount = "Select 
				     			address.*,
								COUNT(*) AS count
							FROM 
								address
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$state_id,
								$state,
								$country,
								$alias,
								$street, 
								$number
						);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
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
								$active, 
								$state_id,
								$state,
								$country,
								$alias,
								$street, 
								$number
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($state_id !== NULL){
				$query .= " AND `state_id` = '".DataHandler::forceInt($state_id)."'";
			}
			if($state !== NULL){
				$query .= " AND `state` = '".DataHandler::forceString($state)."'";
			}
			if($country !== NULL){
				$query .= " AND `country` = '".DataHandler::forceString($country)."'";
			}
			if($alias !== NULL){
				$query .= " AND `alias` = '".DataHandler::forceString($alias)."'";
			}
			if($street !== NULL){
				$query .= " AND `street` = '".DataHandler::forceString($street)."'";
			}
			if($number !== NULL){
				$query .= " AND `number` = '".DataHandler::forceString($number)."'";
			}
			//adiciona a ordenação na query
		}
		
		public function getVO(){
			return new AddressVO();
		}
}