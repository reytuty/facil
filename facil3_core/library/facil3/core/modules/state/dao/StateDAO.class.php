<?php

	/**
	 * @author		: Mauricio
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @desc		: 	Classe para conex�es com o banco de dados mysql
	 					Essa classe precisa que tenha um link de conex�o com o banco aberta
						para evitar abrir conex�es constantemente
	 */
	
	//include_once "e_commerce/modules/state/vo/StateVO.class.php";
	include_once "facil3/core/DbInterface.class.php";
	include_once "facil3/interface/dao/PatternDAO.class.php";

	class StateDAO extends DbInterface implements PatternDAO{
		public static $instance;
		public static function getInstance(){
		if(!self::$instance){
			self::$instance = new StateDAO();
		}
		return self::$instance;
		
		}	public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert($VO->name, $VO->uf, $VO->country_id, $VO->active = 1);
		}
		public function insert($name, $uf, $country_id, $active = 1){
			$query = "INSERT INTO 
						state (
								`id`,
								`active`, 
								`name`, 
								`uf`,
								`country_id`
						)VALUES(
								NULL ,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($uf)."',
								'".DataHandler::forceInt($country_id)."'
						)";
			$ReturnDataVO = parent::query($query, TRUE);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function active($id){
			$ReturnDataVO = $this->update($id, 1);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$ReturnDataVO = $this->update($id, 0);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$ReturnDataVO = $this->deactive($id);
			return $ReturnDataVO;
		}
		
		/**
	     * @param VO
	     * @return class ReturnDataVO 
	     */
		public function updateVO($VO){
			//echo "<li>updateVO</li>";
			return $this->update($VO->getId(), $VO->active, $VO->name, $VO->uf, $VO->country_id);
		}

		/**
		 * @param $id
		 * @param $active
		 * @param $name
		 * @param $uf
		 * @return class ReturnDataVO 
		 */
		public function update($id = NULL, $active = NULL, $name = NULL, $uf = NULL, $country_id = NULL){
			$ReturnDataVO = new ReturnDataVO();
			//echo "<li>update</li>";
			$arrayFilter = array();
			
			$query = "UPDATE state SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->sucess = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$comma = "";
			if($active !== NULL){
				$query .= "$comma  `active` = '".DataHandler::forceInt($active)."'";
				$comma = ", ";
			}
			if($name !== NULL){
				$query .= "$comma `name` = '".DataHandler::forceString($name)."'";
				$comma = ", ";
			}
			if($uf !== NULL){
				$query .= " $comma `uf` = '".DataHandler::forceString($uf)."'";
				$comma = ", ";
			}
			if($country_id !== NULL){
				$query .= " $comma `country_id` = '".DataHandler::forceInt($country_id)."'";
				$comma = ", ";
			}
			$query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			//print_r($query);
			if($comma == ""){
				$ReturnDataVO->sucess = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES;
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				if(!$ReturnDataVO->sucess){
					$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_COMMIT;
				}
				return $ReturnDataVO;
			}
			
		}
		
		
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function selectByUf($uf){
			$ReturnDataVO = $this->select(DbInterface::RETURN_STD_OBJECT, NULL, 1, NULL, $uf);
			return $ReturnDataVO;
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
	     * 
	     * @param  (string) $typeDeRetorno UsuarioDAO::RETORNO_STD_OBJECT, UsuarioDAO::RETORNO_VO, UsuarioDAO::RETORNO_ARRAY 
	     * @param  (int) 		$id  (opcional) 
	     * @param  (int) 		$active (opcional)
	     * @param  (string) 	$name (opcional)
	     * @param  (string) 	$uf (opcional)
	     * @param  (id) 		$country_id (opcional)
	     * @param  (int) 		$quant_limite = NULL
	     * @param  (int) 		$quant_started = NULL
	     * 
	     * @return class ReturnDataVO
	     */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL, 
								$active = NULL, 
								$name = NULL, 
								$uf = NULL,						
								$country_id = NULL,
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
						state.*,
						country.id as country_id,
						country.name as country_name
					  FROM 
					  	state 
					  INNER JOIN country
					  WHERE 1 AND state.country_id = country.id";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$name, 
								$country_id,
								$uf
							);
			
			$queryCount = "Select 
				     			state.*,
								COUNT(*) AS count
							FROM 
								state
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$name, 
								$country_id,
								$uf
						);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			//echo $query;
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
								$name, 
								$country_id,
								$uf
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($uf !== NULL){
				$query .= " AND `uf` = '".DataHandler::forceString($uf)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` = '".DataHandler::forceString($name)."'";
			}
			if($country_id !== NULL){
				$query .= " AND `country_id` = '".DataHandler::forceInt($country_id)."'";
			}
		}

		
		public function getVO(){
			return new StateVO();
		}
	}
	
?>