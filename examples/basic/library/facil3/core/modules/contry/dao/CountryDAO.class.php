<?php

	/**
	 * @author		: Mauricio
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @desc		: 	Classe para conex�es com o banco de dados mysql
	 					Essa classe precisa que tenha um link de conex�o com o banco aberta
						para evitar abrir conex�es constantemente
	 */
	
	//include_once "e_commerce/modules/country/vo/CountryVO.class.php";
	include_once "facil3/core/DbInterface.class.php";
	include_once "facil3/interface/dao/PatternDAO.class.php";

	class CountryDAO extends DbInterface implements PatternDAO{
		public static $instance;
		public static function getInstance(){
		if(!self::$instance){
			self::$instance = new CountryDAO();
		}
		return self::$instance;
		
		}	public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert($VO->name, $VO->language_code, $VO->active = 1, $VO->code);
		}
		public function insert($name, $language_code, $active = 1, $code){
			$query = "INSERT INTO 
						country (
								`id`,
								`active`, 
								`name`, 
								`language_code`,
								`code`
						)VALUES(
								NULL ,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($language_code)."',
								'".DataHandler::forceString($code)."'
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
			return $this->update($VO->getId(), $VO->active, $VO->name, $VO->language_code, $VO->code);
		}

		/**
		 * @param $id
		 * @param $active
		 * @param $name
		 * @param $language_code
		 * @param $code
		 * @return class ReturnDataVO 
		 */
		public function update($id = NULL, $active = NULL, $name = NULL, $language_code = NULL, $code = NULL){
			$ReturnDataVO = new ReturnDataVO();
			//echo "<li>update</li>";
			$arrayFilter = array();
			
			$query = "UPDATE country SET ";
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
			if($language_code !== NULL){
				$query .= " $comma `language_code` = '".DataHandler::forceString($language_code)."'";
				$comma = ", ";
			}
			if($code !== NULL){
				$query .= " $comma `code` = '".DataHandler::forceString($code)."'";
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
	     * @return ReturnDataVO 
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
	     * @param  (string) 	$language_code (opcional)
	     * @param  (string) 	$code (opcional)
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
								$language_code = NULL,
								$code = NULL,				
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
						*
					  FROM 
					  	country 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$name, 
								$language_code,
								$code
							);
			
			$queryCount = "Select 
				     			country.*,
								COUNT(*) AS count
							FROM 
								country
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$name, 
								$language_code,
								$code
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
								$language_code,
								$code
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($language_code !== NULL){
				$query .= " AND `language_code` = '".DataHandler::forceString($language_code)."'";
			}
			if($code !== NULL){
				$query .= " AND `code` = '".DataHandler::forceString($code)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` = '".DataHandler::forceString($name)."'";
			}
		}

		
		public function getVO(){
			return new CountryVO();
		}
	}
	
?>