<?php
	/**
	 * @author		: Alan Lucian
	 * @date		: 20/12/2010
	 * @version		: 1.0
	 * @desc		: essa classe utiliza as tabelas
	 * 				  product_democrart_quadro - para inserir e atualizar atributos em modelos de produtos
	 * 				  view_product_quadro		 - para consultar, deletar, ativar ou desatiavar os modelos de produtos
	 * 					 
	 */

	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	include_once "library/facil3/core/dao/BaseDAO.class.php";

	class NewsletterDAO extends BaseDAO implements PatternDAO{
		
		protected $TABLE_NAME = 'newsletter';
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new NewsletterDAO();
			}
			return self::$instance;
		}
		
		
		public function __construct(){
			parent::__construct();
		}
		
		/* 
		 * @OBS atenção: é enviado o campo id referente a prodct e não product_democrart_quadrado
		 */
		public function insertVO($VO){
			return $this->insert( 
			  	$VO->email
			);
		}
		/**
		 * @param $product_id (int)
		 * @param $material_id (int)
		 * @param $width 
		 * @param $height 
		 * @return $ReturnDataVO (ReturnDataVO)
		 */
		public function insert(
							$active,
	  						$email 
	  					){
			$query = "INSERT INTO 
						".$this->TABLE_NAME." (
								`id`, 
								`active`,
								`email`,
								`date`
								
						)VALUES(
								NULL,
								" . DataHandler::forceInt($active) . ",
								'".$email."',
								NOW()
								)";
			$ReturnDataVO = parent::query($query, TRUE);
			//echo $query;
			//exit();
			return $ReturnDataVO;
		}
		/**
	     * @param VO
	     * @return class ReturnDataVO 
	     * @OBS atenção: é enviado o campo id referente a prodct e não product_democrart_quadrado
	     */
		public function updateVO($VO){
			return $this->update(
					  	$VO->active,
					  	$VO->email 				
				);
		}
		 public function update(
                            $id,
                            $active             = NULL,
                            $email          	= NULL
                        ){
                            
            $ReturnDataVO = new ReturnDataVO();
            $query = "UPDATE ".$this->TABLE_NAME." SET ";
        
            if($id == NULL && $id <= 0){
                $ReturnDataVO->success = FALSE;
                $ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
                return $ReturnDataVO;
            }
            
            $comma = "";
            
            if($active !== NULL){
                $query .= $comma." `active` = ".DataHandler::forceInt($active)."";
                $comma = ", ";
            }
            
            
            if($email !== NULL){
                $query .= $comma." `email` = '".DataHandler::forceInt($email)."'";
			}
			            
            $query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
            if($comma == ""){
                $ReturnDataVO->success      = FALSE;
                $ReturnDataVO->code_return  = DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES;
                return $ReturnDataVO;
            }else{
                $ReturnDataVO = parent::query($query);
                return $ReturnDataVO;
            }
        }//end update
		/**
	     * @param $product_id number
	     * @return class ReturnDataVO
	     */
		public function selectById($id){
			$ReturnDataVO = $this->select(DbInterface::RETURN_STD_OBJECT, $id);
			$ReturnDataVO->result = ($ReturnDataVO->success ? $ReturnDataVO->result[0] : false);
			return $ReturnDataVO;
		}
		/**
		 * @param $typeOfReturn
		 * @param $id
		 * @param $active
		 * @param $email
		 */
		public function select(
								$typeOfReturn = NULL, 
								$id = NULL,  
								$active = NULL, 
								$email = NULL,
								$quant_limit = NULL,
								$quant_started = NULL,
								$date = NULL,
								$date_symbol = ">="
									
						){
			$arrayFilter = array();
			$query = "SELECT *
					  FROM 
					  	 ".$this->TABLE_NAME."
				 	 WHERE 1 ";
			$this->addFilter($query,
								$id, 
								$active, 
								$email,
								$date,
								$date_symbol
								);
			
			$queryCount = "Select 
								COUNT(*) AS count
							FROM 
								".$this->TABLE_NAME."
							WHERE 1
  	  					";
			$this->addFilter($queryCount,
								$id, 
								$active, 
								$email,
								$date,
								$date_symbol
								
			);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			//executa a query
			$ReturnDataVO = parent::query($query);
//			var_dump($query);
			//executa a query
			$ReturnTotalDataVO = parent::query($queryCount);
			
			if($ReturnDataVO->success){
				switch($typeOfReturn){
					case DbInterface::RETURN_ARRAY:
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_ARRAY);
						break;
					case DbInterface::RETURN_VO:
						$ReturnDataVO->success = FALSE;
						$ReturnDataVO->result = "error newsletter not have vo";
//						var_dump($ReturnDataVO);
//						$ReturnDataVO->fetchAllVO($this);
//						exit();
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
		public function addFilter(
								&$query,
								$id, 
								$active, 
								$email,
								$date,
								$date_symbol
								
							){
			if($id !== NULL){
				$query .= " AND id = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($email !== NULL){
				$query .= " AND `email` = '".DataHandler::forceString($email)."'";
			}
			if($date !== NULL){
				$date_symbol = parent::compareSimble($date_symbol);
				$date = parent::dateHandlerScape(DataHandler::convertDateToDB($date));
				$query .= " AND `date` $date_symbol $date ";
			}
		}
		
		public function getVO(){
			return new BaseVO();
		}
	}