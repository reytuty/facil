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
						category_id,
						name,
						slug,
						order

	 */

	include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	
	class CategoryDAO extends DbInterface implements PatternDAO{
		public static $table_name = "category";
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new CategoryDAO();
			}
			return self::$instance;
		}
		
		public function __construct(){
			parent::__construct();
		}
		
		public function insertVO($VO){
			return $this->insert( 
				$VO->active,
			  	$VO->category_id,
				$VO->name,
			  	$VO->slug,
			  	$VO->order	
			);
		}
		/**
		 * @param $active (tinyint)
		 * @param $category_id (int)
		 * @param $name (str)
		 * @param $slug (str)
		 * @param $order (int)
		 * @return $ReturnDataVO (ReturnDataVO)
		 */
		public function insert(
	  						$active = 0, 
	  						$category_id = NULL,
	  						$name = NULL,
	  						$slug = NULL,
	  						$order = NULL
  						){
			
			$query = "INSERT INTO 
						".self::$table_name." (
								`id`, 
		  						`active`, 
		  						`category_id`, 
		  						`name`, 
		  						`slug`, 
		  						`order`
						)VALUES(
								NULL,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceInt($category_id)."',
								'".DataHandler::forceString($name)."',
								'".DataHandler::forceString($slug)."',
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
			$query = "UPDATE ".self::$table_name." SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE ".self::$table_name." SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$query = "DELETE FROM ".self::$table_name." WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
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
					  	$VO->category_id,
						$VO->name,
					  	$VO->slug,
					  	$VO->order
				);
		}
		/**
		 * @param $id
		 * @param $active
		 * @param $category_id
		 * @param $name
		 * @param $slug
		 * @param $order
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function update(
							$id,
							$active = NULL, 
	  						$category_id = NULL,
	  						$name = NULL,
	  						$slug = NULL,
	  						$order = NULL
  						){
 
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE ".self::$table_name." SET ";
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
			if($category_id > 0){
				$query .= $comma." `category_id` = '".DataHandler::forceInt($category_id)."'";
				$comma = ", ";
			}
			if($name !== NULL){
				$query .= $comma." `name` = '".DataHandler::forceString($name)."'";
				$comma = ", ";
			}
			if($slug !== NULL){
				$query .= $comma." `slug` = '".DataHandler::forceString($slug)."'";
				$comma = ", ";
			}
			if($order !== NULL){
				$query .= $comma." `order` = '".DataHandler::forceInt($order)."'";
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
		 * @param $category_id
		 * @param $name
		 * @param $slug
		 * @param $order_type
		 * @param $quant_limit
		 * @param $quant_started
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL, 
								$active = NULL, 
								$category_id = NULL,
								$name = NULL, 
								$slug = NULL,
								$order_type = NULL,						
								$quant_limit = NULL, 
								$quant_started = NULL
							){

			$arrayFilter = array();
			$query = "SELECT 
							*
						FROM 
					  		".self::$table_name." 
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
								$active, 
								$category_id,
								$name,
								$slug, 
								$order_type
							);
			
			$queryCount = "Select 
				     			".self::$table_name.".*,
								COUNT(*) AS count
							FROM 
								".self::$table_name."
							WHERE 1
  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
								$active, 
								$category_id,
								$name,
								$slug, 
								$order_type
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
								$active, 
								$category_id,
								$name,
								$slug, 
								$order_type
							){

			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($category_id !== NULL){
				$query .= " AND `category_id` = '".DataHandler::forceInt($category_id)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` = '".DataHandler::forceString($name)."'";
			}
			if($slug !== NULL){
				$query .= " AND `slug` = '".DataHandler::forceString($slug)."'";
			}
			//adiciona a ordenaÃ§Ã£o na query
			$orderBy = "";
			if($order_type != NULL){
				$order_type = parent::verifyOrderType($order_type);
				$orderBy .= " `order` ".$order_type;
			}
			if($orderBy != NULL){
				$query .= " ORDER BY ".$orderBy;
			}
		}

		/**
		 * @param $typeOfReturn
		 * @param $dad_category_id
		 * @param $active
		 * @return ReturnDataVO (ReturnDataVO)
		 */
		public function selectCascade($typeOfReturn = NULL, $dad_category_id, $active = NULL){
			//lista todas as categorias dentro do filtro
			$ReturnDataVO		= new ReturnDataVO();
			switch($typeOfReturn){
				case DbInterface::RETURN_VO:
					$ReturnDataVO = $this->select(DbInterface::RETURN_VO, NULL, $active);
					break;
				case DbInterface::RETURN_STD_OBJECT:
				default:
					$ReturnDataVO = $this->select(DbInterface::RETURN_STD_OBJECT, NULL, $active);
					//ja esta em stdClass
					break;
			}
			if(!$ReturnDataVO->success){
				//se der erro já retorna o resultado com o erro
				return $ReturnDataVO;
			}

			$return_array = $this->cascadeOrganize($dad_category_id, $ReturnDataVO->result, NULL, $typeOfReturn);

			$ReturnDataVO->success = TRUE;
			$ReturnDataVO->result = $return_array;
			$ReturnDataVO->code_return = DbInterface::SUCCESS;
			return $ReturnDataVO;
		}

		/**
		 * @param int $subCategoria_id
		 * @param array de StdObject $array
		 * @param int $grau
		 * @return array
		 */
		private function cascadeOrganize($dad_category_id, $array, $step = 0, $typeOfReturn = NULL){//retorna uma Array com todos os estilos
			//essa função não para ser usada fora da classe.
			$arrayRetorno = array();
			for($i = 0; $i < count($array); $i++){//varrendo toda a array recebida
				//$this->debug($array[$i]["categoria_id"]." == $subCategoria_id");
				if($array[$i]->category_id == $dad_category_id){//se ele tiver o sub item...
					$arrayTemp 							= $array[$i];
					$arrayTemp->__array_category 		= $this->cascadeOrganize($arrayTemp->id, $array, ($step+1), $typeOfReturn);
					$arrayTemp->step 					= ($step+1);
					
					if($typeOfReturn == CategoryDAO::RETURN_ARRAY){
						$arrayTemp = (array) $arrayTemp;
					}
					
					$arrayRetorno[] = $arrayTemp;
					//Debug::li($i);
					//print_r($arrayRetorno);
					//if($i == 2){
					//	exit();
					//}
				}
			}
			return $arrayRetorno;
		}

		public function getVO(){
			return new CategoryVO();
		}
}