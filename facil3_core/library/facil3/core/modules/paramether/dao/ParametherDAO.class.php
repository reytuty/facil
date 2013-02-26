<?php
	/**
	 * @author		: Diego Faria Montania - diego@midianova.com.br
	 * @date		: 19/02/2010
	 * @version		: 1.0
	 * @tabela		: paramether
	 * @desc		: 	Paramether pode ser por qualquer unidade de valor de qualquer tabela  
	 */
	
	include_once "library/facil3/core/modules/paramether/vo/ParametherVO.class.php";
	include_once "library/facil3/core/modules/paramether/vo/ItemParametherVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	
	class ParametherDAO extends DbInterface implements PatternDAO{

		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new ParametherDAO();
			}
			return self::$instance;
		}

		const SUCCESS 					= 1;
		
		const STATUS_OK 		= "ok";
		const STATUS_INSERT		= "insert";
		const STATUS_UPDATE		= "update";
		const STATUS_DELETE		= "delete";
		
		const TYPE_VARCHAR		= "varchar";
		const TYPE_NUMBER		= "number";
		const TYPE_INT			= "int";
		const TYPE_TEXT			= "text";
		const TYPE_DATE			= "date";
		const TYPE_JSON			= "json";
		
		const RETORNO_OBJ		= "OBJ";
		const RETORNO_VO		= "VO";
		const RETORNO_ARRAY		= "ARRAY";
		
		const ERROR_PARAMETHER_UPDATE_COMMIT 	= "ERROR_PARAMETHER_UPDATE_COMMIT";
		const ERROR_PARAMETHER_INSERT_COMMIT 	= "ERROR_PARAMETHER_INSERT_COMMIT";
		public function __construct(){
			parent::__construct();
		}
		/**
		 * @param (object VO) $VO
	     * @return int
	     */
		public function insertVO($ItemParametherVO){
			return FALSE;//n�o usa pois n�o � assim que se faz. Usa-se sempre atualizar VO
		}
		/**
		 * @param $table
		 * @param $table_id
		 * @param $name
		 * @param $type
		 * @param $active
		 * @param $locale
 		 * @param $varchar
		 * @param $text
		 * @param $number
		 * @param $int
		 * @param $date
		 * @return $ReturnDataDAO
		 */
		public function insert($table, $table_id, $name = NULL, $type = ParametherDAO::TYPE_VARCHAR, $active = 1, $locale = NULL, $varchar = NULL, $text = NULL, $number = NULL, $int = NULL, $date = NULL){
			$date = (DataHandler::convertDateToDB($date) != "--")?DataHandler::convertDateToDB($date):"''";
			$query = "INSERT INTO 
						paramether(
								`paramether`.id,
								`paramether`.table,
								`paramether`.table_id,
								`paramether`.name,
								`paramether`.type,
								`paramether`.varchar,
								`paramether`.text,
								`paramether`.number,
								`paramether`.int,
								`paramether`.date,
								`paramether`.active,
								`paramether`.locale
						)VALUES(
								NULL,
								'".DataHandler::forceString($table)."',
								'".DataHandler::forceInt($table_id)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($type)."',
								'".DataHandler::forceString($varchar)."',
								'".DataHandler::forceString($text)."',
								'".DataHandler::forceNumber($number)."',
								'".DataHandler::forceInt($int)."',
								".$date.",
								'".$active."',
								'".DataHandler::forceString($locale)."'
								)";
			$ReturnDataDAO = parent::query($query);
			//print_r($ReturnDataDAO);
			return $ReturnDataDAO;
		}

		public function active($id){
			return $this->update($VO->id, 1);
		}
		
		public function deactive($id){
			return $this->update($VO->id, 0);
		}


		/**
		 * @param $table
		 * @param $table_id
		 * @param $name
		 * @param $type
		 * @param $active
		 * @param $locale
		 * @param $varchar
		 * @param $text
		 * @param $number
		 * @param $int
		 * @param $date
		 * @return ReturnDataVO
		 */
		public function update($id, $table, $table_id, $name = NULL, $type = ParametherDAO::TYPE_VARCHAR, $active = 1, $locale = NULL, $varchar = NULL, $text = NULL, $number = NULL, $int = NULL, $date = NULL){
			$ReturnDataVO = new ReturnDataVO();
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_PARAMETHER_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$query = "UPDATE `paramether` SET ";

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
			if($name !== NULL){
				$query .= "$comma `name` = '".DataHandler::forceString($name)."'";
				$comma = ", ";
			}
			if($type !== NULL){
				$query .= " $comma `type` = '".DataHandler::forceString($type)."'";
				$comma = " , ";
			}
			if($locale !== NULL){
				$query .= " $comma `locale` = '".DataHandler::forceString($locale)."'";
				$comma = ", ";
			}
			if($varchar !== NULL){
				$query .= " $comma `varchar` = '".DataHandler::forceString($varchar)."'";
				$comma = ", ";
			}
			if($text !== NULL){
				$query .= " $comma `text` = '".DataHandler::forceString($text)."'";
				$comma = ", ";
			}
			if($number !== NULL){
				$query .= " $comma `number` = '".DataHandler::forceNumber($number)."'";
				$comma = ", ";
			}
			if($int !== NULL){
				$query .= " $comma `int` = '".DataHandler::forceInt($int)."'";
				$comma = ", ";
			}
			if($date !== NULL){
				$query .= " $comma `date` = '".DataHandler::convertDateToDB($date)."'";
				$comma = ", ";
			}	 
		 
		 	$query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
		 	//echo $query;
		 	
			if($comma == ""){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_PARAMETHER_UPDATE_DONOT_HAVE_UPDATES;
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				if(!$ReturnDataVO->success){
					$ReturnDataVO->code_return = DbInterface::ERROR_PARAMETHER_UPDATE_COMMIT;
					
				}
				return $ReturnDataVO;
			}		
		}
		
		

		/* (non-PHPdoc)
		 * @see classes/interface/dao/PatternDAO#updateVO($VO)
		 */
		public function updateVO($BaseVO){
			//Debug::li("updateVO chamado.");
			//aqui recebe o ParametherVO
			$arrayParamether = $BaseVO->__arrayParamethers;
			//Debug::print_r($arrayParamether);
			//iniciando array de erros fora do for
			$erros = array();
			for($i = 0; $i < count($arrayParamether); $i++){
				//varrendo array de terms
					$tempVO = $arrayParamether[$i];
					//exit();
					if($tempVO->getStatus() == ParametherDAO::STATUS_OK){
						continue;
					} else {
						if($tempVO->getStatus() == ParametherDAO::STATUS_UPDATE){
							//Debug::li("ParametherDAO . updateVO -> vai alterar essa ParametherVO");
							//alterar uma entrada j� existente - n�o altera seo
							$tempVO->setStatus(ParametherDAO::STATUS_OK);
							//print_r($tempVO);
							$ReturnDataVO = $this->update($tempVO->getId(), $BaseVO->__table, $BaseVO->getId(), $tempVO->getName(), $tempVO->getType(), $tempVO->getActive(), $tempVO->getLocale(), $tempVO->getVarchar(), $tempVO->getText(), $tempVO->getNumber(), $tempVO->getInt(), $tempVO->getDate());
							if(!$ReturnDataVO->success){
								$erros[] = self::ERROR_PARAMETHER_UPDATE_COMMIT;
							}
							//Debug::print_r($BaseVO);
							//exit();
							//Debug::li("erro na ParametherDAO - > ".Debug::print_r($erros));
							//return $ReturnDataVO;
						} else if($tempVO->getStatus() == ParametherDAO::STATUS_INSERT){
							//Debug::li("ParametherDAO . updateVO -> vai inserir essa ParametherVO");
							//incluir nova entrada
							$tempVO->setStatus(ParametherDAO::STATUS_OK);
							$ReturnDataVO = $this->insert($BaseVO->__table, $BaseVO->getId(), $tempVO->getName(), $tempVO->getType(), $tempVO->getActive(), $tempVO->getLocale(), $tempVO->getVarchar(), $tempVO->getText(), $tempVO->getNumber(), $tempVO->getInt(), $tempVO->getDate());
							//Debug::print_r($ReturnDataVO);
							if(!$ReturnDataVO->success){
								$erros[] = self::ERROR_PARAMETHER_INSERT_COMMIT;
							}
						} else if($tempVO->getStatus() == ParametherDAO::STATUS_DELETE){
							//incluir nova entrada
							//Debug::li("ParametherDAO . updateVO -> vai DELETAR essa ParametherVO");
							$ReturnDataVO = $this->delete($tempVO->getId());
							if(!$ReturnDataVO->success){
								$erros[] = self::ERROR_PARAMETHER_INSERT_COMMIT;
							} else {
								$tempVO->setStatus(ParametherDAO::STATUS_OK);
							}
							//var_dump($temp);
						}
				}//end for $j
			}
			$ReturnDataVO = new ReturnDataVO();
			if(count($erros) <= 0){
				$ReturnDataVO->success = TRUE;
				//Debug::li("TUDO CERTO NA ParametherDAO ");
			} else {
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = $erros;
				//Debug::li("erro na ParametherDAO - > ".Debug::print_r($erros));
			}
			return $ReturnDataVO;
		}//end function

		
		function select($table, $table_id, $typeOfReturn = NULL){
			$typeOfReturn = ($typeOfReturn === NULL)?NULL:$typeOfReturn;
			//pegando todos os paramethers com essa table
			$query = "SELECT * 
						FROM 
							paramether
						WHERE
							`paramether`.table = '".$table."'
						AND
							`paramether`.table_id = '".$table_id."'
						";
			$ReturnDataVO = parent::query($query);
		
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
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
		}

		function deleteParamethersByTableId($table, $table_id){
			//vai tirar todos os paramethers existentes com a $table_vinculada de indice $table_vinculada_id
			return parent::query("DELETE FROM paramether WHERE table = '$table' AND table_id = '$table_id'");
		}
		
		function delete($id){
			$query = "DELETE FROM paramether WHERE id = '$id' LIMIT 1";
			return parent::query($query);
		}
		
		public function countTotal($table, $table_id, $active = 1){
			$query = "SELECT count(*) AS quantidade
						FROM 
							paramether 
							WHERE 
									table = '$table'
								AND
									table_id = '$table_id'
								AND 
									active = 'active'
							";
		}

		public function getItemParametherVO(){
			return new ItemParametherVO();
		}

		public function getVO(){
			return new ParametherVO();
		}
	}
?>