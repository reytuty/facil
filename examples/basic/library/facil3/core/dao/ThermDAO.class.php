<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 13/07/2010
	 * @version		: 1.0
	 * @tabela		: term
	 * @desc		: Therm pode ser por valores de qualquer tabela  
	 */
	/**
	 * @author		: Diego Pinga
	 * @date		: 14/07/2010
	 * @version		: 1.0
	 * @desc		: Altera��es:  
	 */
	
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	include_once "library/facil3/core/vo/LinkVO.class.php";
	include_once "library/facil3/core/vo/ThermVO.class.php";
	class ThermDAO extends DbInterface implements PatternDAO{
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new ThermDAO();
			}
			return self::$instance;
		}
		
		const successO 			= 1;
		
		const STATUS_OK 		= "ok";
		const STATUS_INSERT		= "insert";
		const STATUS_UPDATE		= "update";
		const STATUS_DELETE		= "delete";
		public function __construct(){
			parent::__construct();
		}
		/**
		 * @param (object VO) $VO
	     * @return int
	     */
		public function insertVO($ThermVO){
			return FALSE;//n�o usa pois n�o � assim que se faz. Usa-se sempre atualizar VO
		}
		
		/**
		 * @param $table
		 * @param $table_id
		 * @param $therm
		 * @param $locale
		 * @param $value
		 * @param $active
		 * @return unknown_type
		 */
		public function insert($table = NULL, $table_id = NULL, $therm = NULL, $locale = NULL, $value = NULL, $active = 1){
			/*
			if($table == NULL || $table_id == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = DbInterface::ERROR_TERM_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			*/
			$table = ($table == NULL)?"NULL":"'".DataHandler::forceString($table)."'";
			$table_id = ($table_id == NULL)?"NULL":DataHandler::forceInt($table_id);
			$query = "INSERT INTO 
						therm(
								`id`,
								`table`,
								`table_id`,
								`therm`,
								`locale`,
								`value`,
								`active`
						)VALUES(
								NULL,
								'".DataHandler::removeSpecialCharacters($table)."',
								'".DataHandler::forceInt($table_id)."',
								'".DataHandler::forceString($therm)."',
								'".DataHandler::forceString($locale)."',
								'".DataHandler::forceString($value)."',
								'".DataHandler::forceInt($active)."'
								)";
			
			$ReturnDataVO = parent::query($query, TRUE);
			
			if(!$ReturnDataVO->success){
				$ReturnDataVO->result = DbInterface::ERROR_TERM_INSERT_COMMIT;				
			}
			return $ReturnDataVO;
		}
		
		/**
		 * @param $id
		 * @param $active
		 * @param $table
		 * @param $table_id
		 * @param $therm
		 * @param $locale
		 * @param $value
		 * @return unknown_type
		 */
		public function update($id, $active = 1, $table = NULL, $table_id = NULL, $therm = NULL, $locale = NULL, $value = NULL){
			$ReturnDataVO = new ReturnDataVO();
			//echo "<li>update</li>";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_TERM_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$arrayFilter = array();			
			$query = "UPDATE `therm` SET ";
			
			$comma = "";
			if($active !== NULL){
				$query .= "$comma  `active` = '".DataHandler::forceInt($active)."'";
				$comma = ", ";
			}
			if($table !== NULL){
				$query .= " $comma `table` = '".DataHandler::forceString($table)."'";
				$comma = " , ";
			}
			if($table_id !== NULL){
				$query .= " $comma `table_id` = '".DataHandler::forceInt($table_id)."'";
				$comma = " , ";
			}
			if($therm !== NULL){
				$query .= "$comma `therm` = '".DataHandler::forceString($therm)."'";
				$comma = ", ";
			}
			if($locale !== NULL){
				$query .= " $comma `locale` = '".DataHandler::forceString($locale)."'";
				$comma = ", ";
			}
			if($value !== NULL){
				$query .= " $comma `value` = '".DataHandler::forceString($value)."'";
				$comma = " , ";
			}
			$query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			if($comma == ""){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_TERM_UPDATE_DONOT_HAVE_UPDATES;
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				if(!$ReturnDataVO->success){
					$ReturnDataVO->code_return = DbInterface::ERROR_TERM_UPDATE_COMMIT;
					
				}
				return $ReturnDataVO;
			}		
		}
		
		/* (non-PHPdoc)
		 * @see classes/interface/dao/PatternDAO#updateVO($VO)
		 */
		public function updateVO($BaseVO){
			//Debug::li("ThermDAO -> updateVO chamado.");
			//aqui recebe o ThermVO
			$arrayTherm = $BaseVO->__arrayTherms;
			//Debug::print_r($arrayTherm);
			//iniciando array de erros fora do for
			$erros = array();
			for($i = 0; $i < count($arrayTherm); $i++){
				//varrendo array de terms
					$tempVO = $arrayTherm[$i];
					//exit();
					if($tempVO->getStatus() == ThermDAO::STATUS_OK){
						//Debug::li("o termpo [$i] j� est� ok ");
						continue;
					} else {
						if($tempVO->getStatus() == ThermDAO::STATUS_UPDATE){
							//Debug::li("ThermDAO . updateVO -> vai alterar essa ThermVO");
							//alterar uma entrada j� existente - n�o altera seo
							$tempVO->setStatus(ThermDAO::STATUS_OK);
							$ReturnDataVO = $this->update($tempVO->getId(), $tempVO->getActive(), $BaseVO->getTable(), $BaseVO->getTableId(), $tempVO->getTherm(), $tempVO->getLocale(), $tempVO->getValue());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_TERM_UPDATE_COMMIT;
							}
							//Debug::print_r($BaseVO);
							//exit();
							//Debug::li("erro na ThermDAO - > ".Debug::print_r($erros));
							//return $ReturnDataVO;
						} else if($tempVO->getStatus() == ThermDAO::STATUS_INSERT){
							//Debug::li("ThermDAO . updateVO -> vai inserir essa ThermVO");
							//incluir nova entrada
							$tempVO->setStatus(ThermDAO::STATUS_OK);
							$ReturnDataVO = $this->insert($BaseVO->getTable(), $BaseVO->getTableId(), $tempVO->getTherm(), $tempVO->getLocale(), $tempVO->getValue());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_TERM_INSERT_COMMIT;
							}
							//var_dump($temp);
						} else if($tempVO->getStatus() == ThermDAO::STATUS_DELETE){
							//incluir nova entrada
							//Debug::li("ThermDAO . updateVO -> vai DELETAR essa ThermVO");
							$ReturnDataVO = $this->delete($tempVO->getId());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_TERM_INSERT_COMMIT;
							} else {
								$tempVO->setStatus(ThermDAO::STATUS_OK);
							}
							//var_dump($temp);
						}
				}//end for $j
			}
			$ReturnDataVO = new ReturnDataVO();
			if(count($erros) <= 0){
				$ReturnDataVO->success = TRUE;
				//Debug::li("TUDO CERTO NA ThermDAO ");
			} else {
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = $erros;
				//Debug::li("erro na ThermDAO - > ".Debug::print_r($erros));
			}
			return $ReturnDataVO;
		}//end function
		
		/**
		 * @param $table
		 * @param $table_id
		 * @param $localeOfReturn
		 * @return unknown_type
		 */
		function select($table, $table_id, $localeOfReturn = DbInterface::RETURN_ARRAY){
			//pegando todos os terms com essa table
			$query = "SELECT * 
						FROM 
							therm
						WHERE
							`table` = '".$table."'
						AND
							`table_id` = '".$table_id."'
						";
			$ReturnDataVO = parent::query($query);
			if($ReturnDataVO->success){
				$ReturnDataVO = $this->typeOfFetch($ReturnDataVO, $localeOfReturn);
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
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
			//vai tirar todos os terms existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("DELETE FROM `therm` WHERE id = '".$id."' LIMIT 1");
			return $ReturnDataVO;
		}
		function deleteAllByTableAndTableId($table, $table_id){
			//vai tirar todos os terms existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("UPDATE `therm` SET `active` = 0 WHERE `table` = '".$table."' AND `table_id` = '".$table_id."'");
			return $ReturnDataVO;
		}
		
		public function contAll($table, $table_id, $active = 1){
			$query = "SELECT count(*) AS quantidade	FROM therm WHERE 
									`table` = '".$table."'
								AND
									`table_id` = '".$table_id."'
								AND 
									`active` = '".$active."'
							";
			$ReturnDataVO = parent::query($query);
			if($ReturnDataVO->success){
				$ReturnDataVO = $this->typeOfFetch($ReturnDataVO);
				$ReturnDataVO->result = $ReturnDataVO->result[0]["quantidade"];
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
		}
		
		public function typeOfFetch($ReturnDataVO, $localeOfReturn = DbInterface::RETURN_ARRAY){
			switch($localeOfReturn){
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
			return $ReturnDataVO;
		}
		
		public function getVO(){
			return new ThermVO();
		}

		
}