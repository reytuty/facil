<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: tabela content 
	 * 				ATENCAO. essa DAO faz query's utilizando as tabelas LINK e CATEGORY
	 * 					
	 * 
	 *`id`, 
	 *`active`, 
	 *`name`, 
	 *`title`, 
	 *`hat`,
	 *`description`, 
	 *`content`, 
	 *`author`, 
	 *`template_url`, 
	 *`slug`, 
	 *`key_words`, 
	 *`date`, 
	 *`date_in`, 
	 *`date_out`, 
	 *`order`
	 */

	include_once "library/facil3/core/modules/content/vo/ContentVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	
	class ContentDAO extends DbInterface implements PatternDAO{

		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new ContentDAO();
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
						$VO->title,
						$VO->hat,
						$VO->description,
						$VO->content,
						$VO->author,
						$VO->template_url,
						$VO->slug,
						$VO->key_words,
						$VO->date,
						$VO->date_in,
						$VO->date_out,
						$VO->order
			);
		}

		/**
		 * @param $active
		 * @param $name
		 * @param $title
		 * @param $hat
		 * @param $description
		 * @param $content
		 * @param $author
		 * @param $template_url
		 * @param $slug
		 * @param $key_words
		 * @param $date
		 * @param $date_in
		 * @param $date_out
		 * @param $order
		 * @return ReturnDataVO
		 */
		public function insert(
						$active = 0, 
						$name = NULL,
						$title = NULL,
						$hat = NULL,
						$description = NULL,
						$content = NULL,
						$author = NULL,
						$template_url = NULL,
						$slug = NULL,
						$key_words = NULL,
						$date = NUL,
						$date_in = NULL,
						$date_out = NULL,
						$order
		  			){
			$date = ($date == "now()")?"now()":"'".DataHandler::convertDateToDB($date)."'";
			
			$query = "INSERT INTO content (
						 `id`, 
						 `active`, 
						 `name`, 
						 `title`, 
						 `hat`, 
						 `description`, 
						 `content`, 
						 `author`, 
						 `template_url`, 
						 `slug`, 
						 `key_words`, 
						 `date`, 
						 `date_in`, 
						 `date_out`, 
						 `order`
						)VALUES(
								NULL,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($title)."',
								'".DataHandler::forceString($hat)."',
								'".DataHandler::forceString($description)."',
								'".DataHandler::forceString($content)."',
								'".DataHandler::forceString($author)."',
								'".DataHandler::removeSpecialCharacters($template_url)."',
								'".DataHandler::removeSpecialCharacters($slug)."',
								'".DataHandler::forceString($key_words)."',
								".$date.",
								'".DataHandler::convertDateToDB($date_in)."',
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
			$query = "UPDATE content SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE content SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "UPDATE content SET `active` = -1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
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
						$VO->title,
						$VO->hat,
						$VO->description,
						$VO->content,
						$VO->author,
						$VO->template_url,
						$VO->slug,
						$VO->key_words,
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
		 * @param $title
		 * @param $hat
		 * @param $description
		 * @param $content
		 * @param $author
		 * @param $template_url
		 * @param $slug
		 * @param $key_words
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
							$title = NULL,
							$hat = NULL,
							$description = NULL,
							$content = NULL,
							$author = NULL,
							$template_url = NULL,
							$slug = NULL,
							$key_words = NULL,
							$date = NUL,
							$date_in = NULL,
							$date_out = NULL,
							$order
  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE content SET ";
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
			if($title !== NULL){
				$query .= $comma." `title` = '".DataHandler::forceString($title)."'";
				$comma = ", ";
			}
			if($hat !== NULL){
				$query .= $comma." `hat` = '".DataHandler::forceString($hat)."'";
				$comma = ", ";
			}
			if($description !== NULL){
				$query .= $comma." `description` = '".DataHandler::forceString($description)."'";
				$comma = ", ";
			}
			if($content !== NULL){
				$query .= $comma." `content` = '".DataHandler::forceString($content)."'";
				$comma = ", ";
			}
			if($author !== NULL){
				$query .= $comma." `author` = '".DataHandler::forceString($author)."'";
				$comma = ", ";
			}
			if($template_url !== NULL){
				$query .= $comma." `template_url` = '".DataHandler::removeSpecialCharacters($template_url)."'";
				$comma = ", ";
			}
			if($slug !== NULL){
				$query .= $comma." `slug` = '".DataHandler::removeSpecialCharacters($slug)."'";
				$comma = ", ";
			}
			if($key_words !== NULL){
				$query .= $comma." `key_words` = '".DataHandler::forceString($key_words)."'";
				$comma = ", ";
			}
			if($date !== NULL){
				$date = ($date == "now()")?"now()":"'".DataHandler::convertDateToDB($date)."'";
				
				$query .= $comma." `date` = $date ";
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
		 * @desc 
		 * @param $typeOfReturn
		 * @param $id
		 * @param $active
		 * @param $name
		 * @param $title
		 * @param $hat
		 * @param $description
		 * @param $content
		 * @param $author
		 * @param $template_url
		 * @param $slug
		 * @param $key_words
		 * @param $date
		 * @param $date_symbol
		 * @param $date_in
		 * @param $date_in_symbol
		 * @param $date_out
		 * @param $date_out_symbol
		 * @param $order_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @param $search
		 * @param $locale
		 * @return unknown_type
		 */
		public function select(
							$typeOfReturn = NULL, 
							$id = NULL, 
							$active = NULL, 
							$name = NULL,
							$title = NULL,
							$hat = NULL,
							$description = NULL,
							$content = NULL,
							$author = NULL,
							$template_url = NULL,
							$slug = NULL,
							$key_words = NULL,
							$date = NULL,
							$date_symbol = ">=", 
							$date_in = NULL,
							$date_in_symbol = ">=", 
							$date_out = NULL,
							$date_out_symbol = ">=", 
							$order_type = NULL,						
							$quant_limit = NULL, 
							$quant_started = NULL,
							$search = NULL,
							$locale = NULL
						){

			$arrayFilter = array();
//
//SELECT
//	 count(content.id) as count_total ,
//	 content.* 
//FROM 
//	content 
//		INNER JOIN 
//			therm 
//				ON `therm`.table = 'content' 
//				AND `therm`.therm = 'key_words' 
//				AND `therm`.locale = 'pt_BR' 
//				AND `therm`.value LIKE '%bill%' 
//                               
//WHERE 
//	1 
//	AND `content`.active = '1' 
//	AND `content`.id = `therm`.table_id 
// GROUP BY `therm`.table_id
			
			//é necessario passar a string a ser procurado e o locale para ralizar um search
			$is_search = ($search != NULL && $locale != NULL)?TRUE:FALSE;
			if($search){
				$search = urldecode($search);
				$search = DataHandler::cleanStringsForSearch($search);
				$search = str_replace(" ", "%", $search);
			}
			//echo Debug::li("prucura: ".$search." - locale: ".$locale);exit();
			
			$query = "SELECT
							`content`.* ";
			
			$query .= "FROM 
					  		content ";
			
			if($is_search){
			$query .= "INNER JOIN 
							therm 
								ON `therm`.table = 'content' 
								AND `therm`.therm = 'key_words' 
								AND `therm`.locale = '$locale' 
								AND `therm`.value LIKE '%$search%' 
								";
			}
			
			$query .= " WHERE 1 ";
			
			if($is_search){
			$query .= " AND `content`.active = '1' 
						AND `content`.id = `therm`.table_id
						
			";
			}
			
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$name,
								$title,
								$hat,
								$description,
								$content,
								$author,
								$template_url,
								$slug,
								$key_words,
								$date,
								$date_symbol, 
								$date_in,
								$date_in_symbol, 
								$date_out,
								$date_out_symbol, 
								$order_type
							);
			
			$queryCount = "SELECT
							count(content.id) as count_total ";
			
			$queryCount .= " FROM 
					  		content ";
			
			if($is_search){
			$queryCount .= " INNER JOIN 
							therm 
								ON `therm`.table = 'content' 
								AND `therm`.therm = 'key_words' 
								AND `therm`.locale = '$locale' 
								AND `therm`.value LIKE '%$search%' 
								";
			}
						
			$queryCount .= " WHERE 1 ";
			
			if($is_search){
			$queryCount .= " AND `content`.active = '1' 
							 AND `content`.id = `therm`.table_id
			";
			}			
			//Debug::print_r($query);exit();
			//print_r($queryCount);exit();
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$name,
								$title,
								$hat,
								$description,
								$content,
								$author,
								$template_url,
								$slug,
								$key_words,
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
//			Debug::print_r($query);exit();
			//executa a query
			$ReturnDataVO = parent::query($query);
//			echo $queryCount;exit();
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
//				Debug::print_r($ReturnTotalDataVO);exit();
				if(isset($ReturnTotalDataVO->result[0]->count_total)){
					$tempTotal = $ReturnTotalDataVO->result[0]->count_total;
				}else{
					$tempTotal = 0;
				}
				$ReturnDataVO->count_total = $tempTotal;
				
//				echo Debug::print_r($ReturnDataVO);
//				exit();
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
								$title,
								$hat,
								$description,
								$content,
								$author,
								$template_url,
								$slug,
								$key_words,
								$date,
								$date_symbol, 
								$date_in,
								$date_in_symbol, 
								$date_out,
								$date_out_symbol, 
								$order_type
							){

			if($id !== NULL){
				$query .= " AND `content`.id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `content`.active = '".DataHandler::forceInt($active)."'";
			}
			if($name !== NULL){
				$query .= " AND `content`.name = '".DataHandler::forceString($name)."'";
			}
			if($title !== NULL){
				$query .= " AND `content`.title = '".DataHandler::forceString($title)."'";
			}
			if($hat !== NULL){
				$query .= " AND `content`.hat = '".DataHandler::forceString($hat)."'";
			}
			if($description !== NULL){
				$query .= " AND `content`.description = '".DataHandler::forceString($description)."'";
			}
			if($content !== NULL){
				$query .= " AND `content`.content = '".DataHandler::forceString($content)."'";
			}
			if($author !== NULL){
				$query .= " AND `content`.author = '".DataHandler::forceString($author)."'";
			}
			if($template_url !== NULL){
				$query .= " AND `content`.template_url = '".DataHandler::removeSpecialCharacters($template_url)."'";
			}
			if($slug !== NULL){
				$query .= " AND `content`.slug = '".DataHandler::removeSpecialCharacters($slug)."'";
			}
			if($key_words !== NULL){
				$query .= " AND `content`.key_words = '".DataHandler::removeSpecialCharacters($key_words)."'";
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
		 *  content esta linkado, lembrando q o retorno não é a content traduzida pro locale
		 */
		public function selectBySlugAndTableId($slug, $table, $table_id, $locale = NULL, $typeOfReturn = ContentDAO::RETURN_VO, $active = 1){
			if($locale != NULL){
				$query = "
							SELECT 
								`content`.*,
								`".$table."`.id as ".$table."_id,
								`therm`.value
							FROM
							  ".$table."
							INNER JOIN 
							  `link`
							 ON 
							  `link`.table = '".$table."' AND
							  `link`.linked_table = 'content' AND
							  `link`.table_id = `".$table."`.id AND
							  `link`.active = ".$active."
							INNER JOIN
							  therm
							ON
							  `therm`.therm = 'slug' AND
							  `therm`.table = 'content' AND
							  `therm`.table_id = linked_table_id AND
							  `therm`.value = '".$slug."' AND
							  `therm`.locale = '".DataHandler::forceString($locale)."' AND
							  `therm`.active = 1
							INNER JOIN 
							  `content`
							 ON 
							  `content`.id = linked_table_id 
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."' AND
							  `".$table."`.active = ".$active."
							  ORDER BY `link`.order
						";
			}else{
				$query = "
							SELECT 
								`content`.*,
								`".$table."`.id as ".$table."_id
							FROM
							  ".$table."
							INNER JOIN 
							  `link`
							 ON 
							  `link`.table = '".$table."' AND
							  `link`.linked_table = 'content' AND
							  `link`.table_id = `".$table."`.id AND
							  `link`.active = ".$active."
							INNER JOIN 
							  `content`
							 ON 
							  `content`.id = linked_table_id 
							  ";
							if($slug){
								$query .= "AND `content`.slug = '".$slug."'";
							}
							$query .= "
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."' AND
							  `".$table."`.active = ".$active."
							  ORDER BY `link`.order
						";
			}
			//executa a query
			$ReturnDataVO = parent::query($query);
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
				return $ReturnDataVO;
			} else {
				return $ReturnDataVO;
			}
		}
		
		
		/**
		 * @param $table
		 * @param $table_id
		 * @param $typeOfReturn
		 * @param $quant_limit
		 * @param $quant_started
		 * @return $ReturnDataVO
		 */
		public function selectByTableId($table, $table_id, $typeOfReturn = ContentDAO::RETURN_STD_OBJECT, $quant_limit = NULL, $quant_started = NULL, $active = 1, $search = "", $order_colunm = "order", $order_type = "ASC"){
				$search_query = ""; 
				if($search != ""){
					$search = DataHandler::forceString($search);
					$search_query = " AND
								(`content`.`title` LIKE '$search' OR
								`content`.`content` LIKE '$search' 
								)
						";
				}
				$query = "
							SELECT 
								`content`.*,
								`".$table."`.id as ".$table."_id
							FROM
							  ".$table."
							INNER JOIN 
							  `link`
							 ON 
							  `link`.table = '".$table."' AND
							  `link`.linked_table = 'content' AND
							  `link`.table_id = `".$table."`.id AND
							  `link`.active = ".$active."
							INNER JOIN 
							  `content`
							 ON 
							  `content`.id = linked_table_id 
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."' AND
							  `".$table."`.active = ".$active."
							  $search_query
							 ORDER BY `link`.$order_colunm $order_type
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
							  `link`.linked_table = 'content' AND
							  `link`.table_id = `".$table."`.id AND
							  `link`.active = ".$active."
							INNER JOIN 
							  `content`
							 ON 
							  `content`.id = linked_table_id 
							WHERE
							  1 AND
							 `".$table."`.id = '".$table_id."' AND
							  `".$table."`.active = ".$active."
							  $search_query
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
			return new ContentVO();
		}
}