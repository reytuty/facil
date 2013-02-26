<?php
	/**
	 * @author			: Diego Montania
	 * @date			: 16/07/2010
	 * @version			: 1.0
	 * @description		: 	Cadastra link, que � o mesmo que v�nculo
	 						Pode-se vincular qualquer item que tenha tabela e id com outro item com tabela e id
	 						Tamb�m pode-se usar tabelas virtuais
	 						
	 						id, 
	 						table, 
	 						table_id, 
	 						linked_table, 
	 						linked_table_id, 
	 						active
	 						
	 						
	 						id,
	 						active, 
	 						table, 
	 						table_id, 
	 						linked_table, 
	 						linked_table_id, 
	 						order
	 */
	
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	include_once "library/facil3/core/vo/LinkVO.class.php";
	class LinkDAO extends DbInterface implements PatternDAO{
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new LinkDAO();
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
		public function insertVO($LinkVO){
			return FALSE;//n�o usa pois n�o � assim que se faz. Usa-se sempre atualizar VO
		}
		/**
		 * @param string $table required
		 * @param int $table_id required
		 * @param string $linked_table required
		 * @param int $linked_table_id required
		 * @param int $active
		 * @return ReturnDataVO|unknown
		 */
		public function insert($table = NULL, $table_id = NULL, $linked_table = NULL, $linked_table_id = NULL, $active = 1, $order = 10){
			//Debug::li("LinkDAO . insert chamado [$table , $table_id , $linked_table , $linked_table_id , $active, $order ]");
			//exit();
			if($table == NULL || $table_id == NULL || $linked_table == NULL || $linked_table_id == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			//order com valor default
			$order = DataHandler::forceInt($order);
			
			//verifica se já existe
			$returnResultVO = $this->select(self::RETURN_STD_OBJECT, DataHandler::removeSpecialCharacters($table), DataHandler::forceInt($table_id), 
			DataHandler::removeSpecialCharacters($linked_table), DataHandler::forceInt($linked_table_id), 0);
			if($returnResultVO->success && count($returnResultVO->result) > 0){
				//já existe um link identico, ve se tem muitos mais e deleta todos, deixa um e usa ele
				if(count($returnResultVO->result) > 1){
					//tem mais que 1, tira o resto deixa só o primeiro
					for($i = 1; $i < count($returnResultVO->result); $i++){
						//deletando um a um, menos o primeiro
						$stdTemp = $returnResultVO->result[$i];
						$this->delete($stdTemp->id);
					}
				}
				//agora da o update no que sobrou, ou seja, o primeiro
				$StdLinkTemp = $returnResultVO->result[0];
				//dando updante no primeiro
				return $this->update($StdLinkTemp->id, 1, NULL, NULL, NULL, NULL, $order);
			}
			
			$query = "INSERT INTO 
						`link`(
								`id`,
								`table`, 
		 						`table_id`, 
		 						`linked_table`, 
		 						`linked_table_id`, 
		 						`active`,
		 						`order`
						)VALUES(
								NULL,
								'".DataHandler::removeSpecialCharacters($table)."',
								'".DataHandler::forceInt($table_id)."',
								'".DataHandler::removeSpecialCharacters($linked_table)."',
								'".DataHandler::forceInt($linked_table_id)."',
								'".DataHandler::forceInt($active)."',
								'".$order."'
								)";
			//Debug::li("LinkDAO . insert : $query ");exit();
			$ReturnDataVO = parent::query($query, TRUE);
			
			if(!$ReturnDataVO->success){
				$ReturnDataVO->result = DbInterface::ERROR_LINK_INSERT_COMMIT;				
			}
			return $ReturnDataVO;
		}
		
		/**
		 * @param $id
		 * @param $active
		 * @param $table
		 * @param $table_id
		 * @param $guide_name
		 * @param $locale
		 * @param $link
		 * @return unknown_type
		 */
		public function update($id, $active = 1, $table = NULL, $table_id = NULL, $linked_table = NULL, $linked_table_id = NULL, $order = NULL){
			$ReturnDataVO = new ReturnDataVO();
			//echo "<li>update</li>";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$arrayFilter = array();			
			$query = "UPDATE link SET ";
			
			$comma = "";
			if($active !== NULL){
				$query .= "$comma  `active` = '".DataHandler::forceInt($active)."'";
				$comma = ", ";
			}
			if($table != NULL){
				$query .= " $comma `table` = '".DataHandler::forceString($table)."'";
				$comma = " , ";
			}
			if($table_id != NULL){
				$query .= " $comma `table_id` = '".DataHandler::forceInt($table_id)."'";
				$comma = " , ";
			}
			if($linked_table != NULL){
				$query .= " $comma `linked_table` = '".DataHandler::forceString($linked_table)."'";
				$comma = ", ";
			}
			if($linked_table_id != NULL){
				$query .= "$comma `linked_table_id` = '".DataHandler::forceInt($linked_table_id)."'";
				$comma = ", ";
			}
			if($order !== NULL){
				$query .= "$comma `order` = '".DataHandler::forceInt($order)."'";
				$comma = ", ";
			}
			$query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			if($comma == ""){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_UPDATE_DONOT_HAVE_UPDATES;
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				if(!$ReturnDataVO->success){
					$ReturnDataVO->code_return = DbInterface::ERROR_LINK_UPDATE_COMMIT;
					
				}
				return $ReturnDataVO;
			}		
		}
		/* (non-PHPdoc)
		 * @see classes/interface/dao/PatternDAO#updateVO($VO)
		 */
		public function updateVO($BaseVO){
			//Debug::li("LinkDAO -> updateVO chamado.");
			//aqui recebe o LinkVO
			$arrayLink = $BaseVO->__arrayLinks;
			//Debug::print_r($arrayLink);
			//iniciando array de erros fora do for
			//exit();
			$erros = array();
			for($i = 0; $i < count($arrayLink); $i++){
				//varrendo array de links
					$tempVO = $arrayLink[$i];
					//exit();
					if($tempVO->getStatus() == LinkDAO::STATUS_OK){
						//Debug::li("o linkpo [$i] j� est� ok ");
						continue;
					} else {
						if($tempVO->getStatus() == LinkDAO::STATUS_UPDATE){
							//Debug::li("LinkDAO . updateVO -> vai alterar essa LinkVO");
							//alterar uma entrada j� existente - n�o altera seo
							$tempVO->setStatus(LinkDAO::STATUS_OK);
							$ReturnDataVO = $this->update($tempVO->getId(), $tempVO->getActive(), $BaseVO->getTable(), $BaseVO->getTableId(), $tempVO->getLinkedTable(), $tempVO->getLinkedTableId(), $tempVO->getOrder());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_LINK_UPDATE_COMMIT;
							}
							//Debug::print_r($BaseVO);
							//exit();
							//Debug::li("erro na LinkDAO - > ".Debug::print_r($erros));
							//return $ReturnDataVO;
						} else if($tempVO->getStatus() == LinkDAO::STATUS_INSERT){
							//Debug::li("LinkDAO . updateVO -> vai inserir essa LinkVO");
							//incluir nova entrada
							$tempVO->setStatus(LinkDAO::STATUS_OK);
							$ReturnDataVO = $this->insert($BaseVO->getTable(), $BaseVO->getTableId(), $tempVO->getLinkedTable(), $tempVO->getLinkedTableId(), $tempVO->getActive(), $tempVO->getOrder());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_LINK_INSERT_COMMIT;
							}
							//var_dump($temp);
						} else if($tempVO->getStatus() == LinkDAO::STATUS_DELETE){
							//incluir nova entrada
							//Debug::li("LinkDAO . updateVO -> vai DELETAR essa LinkVO");
							$ReturnDataVO = $this->delete($tempVO->getId());
							if(!$ReturnDataVO->success){
								$erros[] = DbInterface::ERROR_LINK_INSERT_COMMIT;
							} else {
								$tempVO->setStatus(LinkDAO::STATUS_OK);
							}
						}
				}//end for $j
			}
			$ReturnDataVO = new ReturnDataVO();
			if(count($erros) <= 0){
				$ReturnDataVO->success = TRUE;
				//Debug::li("TUDO CERTO NA LinkDAO ");
			} else {
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = $erros;
				//Debug::li("erro na LinkDAO - > ".Debug::print_r($erros));
			}
			return $ReturnDataVO;
		}//end function
		
		/**
		 * @param $typeOfReturn
		 * @param $table
		 * @param $table_id
		 * @param $linked_table
		 * @param $linked_table_id
		 * @param $active
		 * @param $quant_started
		 * @param $quant_limit
		 * @return (ReturnDataVO) $ReturnDataVO
		 */
		function select($typeOfReturn = DbInterface::RETURN_ARRAY, $table = NULL, $table_id = NULL, $linked_table = NULL, $linked_table_id = NULL, $active = NULL, $quant_started = NULL, $quant_limit = NULL, $order_by = "order", $order_type = " ASC "){
			/*if($table == NULL || $table_id == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}*/
			//pegando todos os links com essa table
			$query = "SELECT * 
						FROM 
							`link`
						WHERE
							1
						
						";
			
			$queryCount = "SELECT Count(*) as count
								FROM 
									`link`
								WHERE
									1
							";
			
			
			if($table != NULL){
				$query .= " AND `table` = '$table' ";
				$queryCount .= " AND `table` = '$table' ";
			}
			if($table_id != NULL){
				$query .= " AND `table_id` = '$table_id' ";
				$queryCount .= " AND `table_id` = '$table_id' ";
			}
			
			if($linked_table != NULL){
				$query .= " AND linked_table = '$linked_table' ";
				$queryCount .= " AND linked_table = '$linked_table' ";
			}
			if($linked_table_id != NULL){
				$query .= " AND linked_table_id = '".DataHandler::forceInt($linked_table_id)."' ";
				$queryCount .= " AND linked_table_id = '".DataHandler::forceInt($linked_table_id)."' ";
			}
			if($active != NULL){
				$query .= " AND active = '".DataHandler::forceInt($active)."' ";
				$queryCount .= " AND active = '".DataHandler::forceInt($active)."' ";
			}
			$query .= " ORDER BY `$order_by` $order_type";
			
				//Debug::li($query);
			
			
			$query .= parent::limit($quant_limit, $quant_started);
			$ReturnDataVO = parent::query($query);
			//echo $query;
			$ReturnTotalDataVO = parent::query($queryCount);
			//echo $queryCount;
			//print_r($ReturnTotalDataVO);exit();
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
				
				// echo Debug::print_r($ReturnDataVO);
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
			//vai tirar todos os links existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("DELETE FROM `link` WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1");
			return $ReturnDataVO;
		}
		/**
		 * se vc quiser remover todos vinculos a alguém que te vincula, ou seja
		 * tirar os vinculos sabendo apenas o linked_table e linked_table_id 
		 * @param $linked_table
		 * @param $linked_table_id
		 * @return ReturnDataVO
		 */
		public function deleteAllFromLinkedTableAndLinkedTableId($linked_table = NULL, $linked_table_id = NULL){
			if($linked_table_id == NULL || $linked_table == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			//vai tirar todos os links existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("UPDATE `link` SET `active` = 0 WHERE linked_table = '$linked_table' AND linked_table_id = '$linked_table_id'");
			return $ReturnDataVO;
		}
		/**
		 * @param $linked_table string da tabela que está vinculada
		 * @param $table string da tabela que tem coisas vinculadas
		 * @param $table_id int do item na tabela que tem coisas vinculadas
		 * @return ReturnDataVO
		 */
		public function deleteAllFromLinkedTableByTableAndTableId($linked_table = NULL, $linked_table_id = NULL, $table = NULL){
			if($table == NULL || $linked_table_id == NULL || $linked_table == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			//vai tirar todos os links existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("UPDATE `link` SET `active` = 0 WHERE `table` = '".$table."' AND linked_table_id = '".$linked_table_id."' AND linked_table = '$linked_table'");
			return $ReturnDataVO;
		}
		/**
		 * @param $table string do item que é quem tem a coisa vinculada 
		 * @param $table_id int do item que tem a coisa vinculada
		 * @return ReturnDataVO
		 */
		public function deleteAllByTableAndTableId($table, $table_id){
			if($table == NULL || $table_id == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			//vai tirar todos os links existentes com a $table_vinculada de indice $table_vinculada_id
			$ReturnDataVO = parent::query("UPDATE `link` SET `active` = 0 WHERE `table` = '".$table."' AND table_id = '".$table_id."'");
			return $ReturnDataVO;
		}
		public function contAll($table, $table_id, $active = 1){
			if($table == NULL || $table_id == NULL){
				$ReturnDataVO = new ReturnDataVO();
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID;
				return $ReturnDataVO;
			}
			$query = "SELECT count(*) AS quantidade	FROM `link` WHERE 
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
		
		public function typeOfFetch($ReturnDataVO, $returnType = DbInterface::RETURN_ARRAY){
			switch($returnType){
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
			return new LinkVO();
		}

		
}