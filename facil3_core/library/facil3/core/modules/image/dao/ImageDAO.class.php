<?php
	/**
	 * @author		: Mauricio Amorim <eu@mauricioamorim.com.br>
	 * @date		: 18/07/2010
	 * @version		: 1.0
	 * @desc		: 	Classe para conex�es com o banco de dados mysql
	 					Essa classe precisa que tenha um link de conex�o com o banco aberta
						para evitar abrir conex�es constantemente
						id, 
  						active, 
  						type, 
  						url,
  						name,
  						description,
  						order,
  						locale,
  						author
	 */
	
	include_once "library/facil3/core/modules/image/vo/ImageVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	

	class ImageDAO extends DbInterface implements PatternDAO{
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new ImageDAO();
			}
			return self::$instance;
		}
		
		public function __construct(){
			parent::__construct();
		}
		public function insertVO($VO){
			return $this->insert($VO->url, $VO->active, $VO->type, $VO->name, $VO->description, $VO->author, $VO->order, $VO->locale);
		}
		public function insert($url, $active = 1, $type = NULL, $name = NULL, $description = NULL, $author = NULL, $order = NULL, $locale = NULL){
			$query = "INSERT INTO 
						`image` (
								`id`, 
			  					`active`, 
		  						`type`, 
		  						`url`,
		  						`name`,
		  						`description`,
		  						`order`,
		  						`author`,
		  						`locale`
						)VALUES(
								NULL ,
								'".DataHandler::forceInt($active)."',
								'".DataHandler::forceString($type)."', 
			  					'".DataHandler::forceString($url)."',
								'".DataHandler::forceString($name)."', 
			  					'".DataHandler::forceString($description)."',
								'".DataHandler::forceInt($order)."',
								'".DataHandler::forceString($author)."',
								'".DataHandler::forceString($locale)."'
						)";
			//echo $query;
			return parent::query($query, TRUE);
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
			return $this->update($VO->getId(), $VO->getUrl(), $VO->getActive(), $VO->getType(), $VO->getName(), $VO->getDescription(), $VO->getAuthor(), $VO->getOrder(), $VO->getLocale());
		}

		
		public function update($id = NULL, $url = NULL, $active = 1, $type = NULL, $name = NULL, $description = NULL, $author = NULL, $order = NULL, $locale = NULL){
			$ReturnDataVO = new ReturnDataVO();
			$arrayFilter = array();
			
			$query = "UPDATE `image` SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
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
			if($url !== NULL){
				$query .= "$comma `url` = '".DataHandler::forceString($url)."'";
				$comma = ", ";
			}
			if($type !== NULL){
				$query .= "$comma `type` = '".DataHandler::forceString($type)."'";
				$comma = ", ";
			}
			if($description !== NULL){
				$query .= "$comma `description` = '".DataHandler::forceString($description)."'";
				$comma = ", ";
			}
			if($order !== NULL){
				$query .= "$comma `order` = '".DataHandler::forceInt($order)."'";
				$comma = ", ";
			}
			if($author !== NULL){
				$query .= "$comma `author` = '".DataHandler::forceString($author)."'";
				$comma = ", ";
			}
			if($locale !== NULL){
				$query .= "$comma `locale` = '".DataHandler::forceString($locale)."'";
				$comma = ", ";
			}
			
			
			$query .= " WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			if($comma == ""){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES;
				return $ReturnDataVO;
			}else{
				$ReturnDataVO = parent::query($query);
				if(!$ReturnDataVO->success){
					$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_COMMIT;
				}
				return $ReturnDataVO;
			}
			
		}
		/**
	     * @param $id number
	     * @param $returna_vo boolean
	     * @return class ReturnDataVO 
	     */
		public function selectById($id, $returna_vo = FALSE){
			$ReturnDataVO = $this->select(DbInterface::RETURN_VO, $id);
			return $ReturnDataVO;
		}
		
		
		public function select($typeOfReturn = NULL, $id = NULL, $active = NULL, $name = NULL, $url = NULL, $author = NULL, $order_type = DbInterface::ORDER_ASC, $quant_limit = NULL, $quant_started = NULL){
			$typeOfReturn = ($typeOfReturn === NULL)?NULL:$typeOfReturn;
			$order_type = parent::verifyOrderType($order_type);
			//echo "login[".$login."] - password[".$password."]";
			$arrayFilter = array();
			$query = "SELECT * FROM `image` WHERE 1";
			if($id !== NULL){
				$query .= " AND `id` = '".DataHandler::forceInt($id)."'";
			}
			if($active !== NULL){
				$query .= " AND `active` = '".DataHandler::forceInt($active)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` = '".DataHandler::forceString($name)."'";
			}
			if($url !== NULL){
				$query .= " AND `url` = '".DataHandler::forceString($url)."'";
			}
			if($author !== NULL){
				$query .= " AND `author` = '".DataHandler::forceString($author)."'";
			}
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
			
			$query .= " ORDER BY `order` $order_type ";
			//executa a query
			$ReturnDataVO = parent::query($query);
			//verifica se conseguiu ocorreu tudo certo
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

		public function getVO(){
			return new ImageVO();
		}
	}
	
?>