<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: tabela highlights 
	 * 				 
	 *`id`, 
	 *`active`, 
	 *`name`, 
	 *`link`,
	 *`content`, 
	 *`image_url`, 
	 *`date`, 
	 *`date_in`, 
	 *`date_out`, 
	 *`order`
	 */

	include_once "library/facil3/core/modules/highlights/vo/HighLightsVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	
	class HighLightsDAO extends DbInterface implements PatternDAO{

		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new HighLightsDAO();
			}
			return self::$instance;
		}
		
		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
						$VO->active, 
						$VO->name,
						$VO->link,
						$VO->content,
						$VO->image_url,
						$VO->date,
						$VO->date_in,
						$VO->date_out,
						$VO->order
			);
		}

		/**
		 * @param $active
		 * @param $name
		 * @param $link
		 * @param $content
		 * @param $image_url
		 * @param $date
		 * @param $date_in
		 * @param $date_out
		 * @param $order
		 * @return ReturnDataVO
		 */
		public function insert(
						$active = 0, 
						$name = NULL,
						$link = NULL,
						$content = NULL,
						$image_url = NULL,
						$date = NUL,
						$date_in = NULL,
						$date_out = NULL,
						$order
		  			){
			
			$query = "INSERT INTO highlights (
						 `id`, 
						 `active`, 
						 `name`, 
						 `link`, 
						 `content`, 
						 `image_url`, 
						 `date`, 
						 `date_in`, 
						 `date_out`, 
						 `order`
						)VALUES(
								NULL,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($link)."',
								'".DataHandler::forceString($content)."',
								'".DataHandler::removeSpecialCharacters($image_url)."',
								'".DataHandler::convertDateToDB($date)."',
								NOW(),
								'".DataHandler::convertDateToDB($date_out)."',
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
			$query = "UPDATE highlights SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE highlights SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "UPDATE highlights SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
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
						$VO->id, 
						$VO->active, 
						$VO->name,
						$VO->link,
						$VO->content,
						$VO->image_url,
						$VO->date,
						$VO->date_in,
						$VO->date_out,
						$VO->order
						
				);
		}
		
		/**
		 * @param $id
		 * @param $active
		 * @param $name
		 * @param $link
		 * @param $content
		 * @param $image_url
		 * @param $date
		 * @param $date_in
		 * @param $date_out
		 * @param $order
		 * @return ReturnDataVO
		 */
		public function update(
							$id,
							$active = 0, 
							$name = NULL,
							$link = NULL,
							$content = NULL,
							$image_url = NULL,
							$date = NUL,
							$date_in = NULL,
							$date_out = NULL,
							$order
  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE highlights SET ";
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
			if($name !== NULL){
				$query .= $comma." `name` = '".DataHandler::forceString($name)."'";
				$comma = ", ";
			}
			if($link !== NULL){
				$query .= $comma." `link` = '".DataHandler::forceString($link)."'";
				$comma = ", ";
			}
			if($content !== NULL){
				$query .= $comma." `content` = '".DataHandler::forceString($content)."'";
				$comma = ", ";
			}
			if($image_url !== NULL){
				$query .= $comma." `image_url` = '".DataHandler::removeSpecialCharacters($image_url)."'";
				$comma = ", ";
			}
			if($date !== NULL){
				$query .= $comma." `date` = '".DataHandler::convertDateToDB($date)."'";
				$comma = ", ";
			}
			if($date_in !== NULL){
				$query .= $comma." `date_in` = '".DataHandler::convertDateToDB($date_in)."'";
				$comma = ", ";
			}
			if($date_out !== NULL){
				$query .= $comma." `date_out` = '".DataHandler::convertDateToDB($date_out)."'";
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
		 * @param $name
		 * @param $link
		 * @param $content
		 * @param $image_url
		 * @param $date
		 * @param $date_symbol
		 * @param $date_in
		 * @param $date_in_symbol
		 * @param $date_out
		 * @param $date_out_symbol
		 * @param $order_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function select(
							$typeOfReturn = NULL, 
							$id = NULL, 
							$active = NULL, 
							$name = NULL,
							$link = NULL,
							$content = NULL,
							$image_url = NULL,
							$date = NULL,
							$date_symbol = ">=", 
							$date_in = NULL,
							$date_in_symbol = ">=", 
							$date_out = NULL,
							$date_out_symbol = ">=", 
							$order_type = NULL,						
							$quant_limit = NULL, 
							$quant_started = NULL
						){

			$arrayFilter = array();
			$query = "SELECT 
							* 
						FROM 
					  		highlights 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$name,
								$link,
								$content,
								$image_url,
								$date,
								$date_symbol, 
								$date_in,
								$date_in_symbol, 
								$date_out,
								$date_out_symbol, 
								$order_type						
							);
			
			$queryCount = "Select 
								COUNT(*) AS count
							FROM 
								highlights
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$name,
								$link,
								$content,
								$image_url,
								$date,
								$date_symbol, 
								$date_in,
								$date_in_symbol, 
								$date_out,
								$date_out_symbol, 
								$order_type						
							);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			$ReturnDataVO = parent::query($query);
			//echo $query;
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
								$link,
								$content,
								$image_url,
								$date,
								$date_symbol, 
								$date_in,
								$date_in_symbol, 
								$date_out,
								$date_out_symbol, 
								$order_type
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` = '".DataHandler::forceString($name)."'";
			}
			if($link !== NULL){
				$query .= " AND `link` = '".DataHandler::forceString($link)."'";
			}
			if($content !== NULL){
				$query .= " AND `content` = '".DataHandler::forceString($content)."'";
			}
			if($image_url !== NULL){
				$query .= " AND `image_url` = '".DataHandler::removeSpecialCharacters($image_url)."'";
			}
			if($date !== NULL){
				$date_symbol = parent::compareSimble($date_symbol);
				$date = parent::dateHandlerScape($date);
				$query .= " AND `date` $date_symbol $date ";
			}
			if($date_in !== NULL){
				$date_in_symbol = parent::compareSimble($date_in_symbol);
				$date_in = parent::dateHandlerScape($date_in);
				$query .= " AND `date_in` $date_in_symbol $date_in ";
			}
			if($date_out !== NULL){
				$date_out_symbol = parent::compareSimble($date_out_symbol);
				$date_out = parent::dateHandlerScape($date_out);
				$query .= " AND `date_out` $date_out_symbol $date_out ";
			}
			//adiciona a ordenaÃ§Ã£o na query
			$orderBy = "";
			if($order_type != NULL){
				$order_type = parent::verifyOrderType($order_type);
				$orderBy .= ", `order` ".$order_type;
			}
			if($orderBy != NULL){
				$query .= " ORDER BY ".$orderBy;
			}
		}

			/**
		 * @param $slug
		 * @param $table
		 * @param $table_id
		 * @param $locale
		 * @param $typeOfReturn
		 * @return $ReturnDataVO
		 * @desc: tabela content ATENCAO. esse metodo busca contents atraves do id do nome da tabela a que esse
		 *  content esta linkado, lembrando q o retorno não é a highlights traduzida pro locale
		 */
		/**
		 * @param $table
		 * @param $table_id
		 * @param $typeOfReturn
		 * @param $quant_limit
		 * @param $quant_started
		 * @return $ReturnDataVO
		 */
		public function selectByTableId($table, $table_id, $typeOfReturn = HighLightsDAO::RETURN_STD_OBJECT, $quant_limit = NULL, $quant_started = NULL){
				$query = "
							SELECT 
								`highlights`.*,
								`".$table."`.id as ".$table."_id
							FROM
							  ".$table."
							INNER JOIN 
							  `link`
							 ON 
							  `link`.table = '".$table."' AND
							  `link`.linked_table = 'highlights' AND
							  `link`.table_id = `".$table."`.id
							INNER JOIN 
							  `highlights`
							 ON 
							  `highlights`.id = linked_table_id 
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."'
						";

				$queryCount = "
							SELECT 
								COUNT(*) AS count
							FROM
							  ".$table."
							INNER JOIN 
							  `link`
							 ON 
							  `link`.table = '".$table."' AND
							  `link`.linked_table = 'highlights' AND
							  `link`.table_id = `".$table."`.id
							INNER JOIN 
							  `highlights`
							 ON 
							  `highlights`.id = linked_table_id 
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."'
						";

			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			$ReturnDataVO = parent::query($query);
			//echo $query;
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

		public function getVO(){
			return new HighLightsVO();
		}
}