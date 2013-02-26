<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 29/11/2010
	 * @version		: 1.0
	 * @desc		:   
	 * 
	 *tabelas com padrao ,  que também utiliza a tabela user
	 					`id`, 
	 					`address_id`, 
	 					`name`, 
	 					`last_name`, 
	 					`birthday`, 
	 					`mobile`, 
	 					`telephone`, 
	 					`email`, 
	 					`rg`, 
	 					`cpf`, 
	 					`company`, 
	 					`website`, 
	 					`sex`, 
	 					`recive_news`, 
	 					`registred`
	 */

	include_once Config::FOLDER_APPLICATION."modules/user_detail/vo/UserDetailVO.class.php";
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	include_once "library/facil3/core/modules/user/dao/UserDAO.class.php";
	class UserDetailDAO extends DbInterface implements PatternDAO{
		public static $_userDAO;
		public static $instance;
		public static function getInstance(){
			if(!self::$instance){
				self::$instance = new UserDetailDAO();
			}
			return self::$instance;
		}
		
		
		public function __construct(){
			parent::__construct();
		}
		/* (non-PHPdoc)
		 * @see library/facil3/interface/dao/PatternDAO#insertVO($VO)
		 */
		public function insertVO($VO){
			//echo Debug::li("chegou aqui");
			//exit();
			$this->getUserDAO();
			$tempReturnDataVO = self::$_userDAO->insertVO($VO);
			if($tempReturnDataVO->success){
				//user cadastrado
				if($tempReturnDataVO->getReturnId() > 0){
					$VO->id = $tempReturnDataVO->getReturnId();
					$ReturnDataVO =  $this->insert(
		 					$VO->getId(), 
		 					$VO->address_id, 
		 					$VO->name, 
		 					$VO->last_name, 
		 					$VO->birthday, 
		 					$VO->mobile, 
		 					$VO->telephone, 
		 					$VO->email, 
		 					$VO->rg, 
		 					$VO->cpf, 
		 					$VO->company, 
		 					$VO->website, 
		 					$VO->sex, 
		 					$VO->recive_news, 
		 					$VO->registred
  						);
  					if($ReturnDataVO->success){
  						return $ReturnDataVO;
  					} else {
  						self::$_userDAO->query("DELETE FROM user WHERE id = '".$VO->id."' LIMIT 1 ");
  						return $ReturnDataVO;
  					}
				}
			}
			//em caso de erro retorna o erro que deu na insert
			return $tempReturnDataVO;
		}
		
		
		public function getUserByEmail($email){
			$ReturnDataVO = new ReturnDataVO();
			$this->getUserDAO();
			$ReturnDataVO = self::$_userDAO->select(UserDAO::RETURN_STD_OBJECT, NULL, 1, NULL, $email, NULL, NULL, 1);
			
			if(!$ReturnDataVO->success || count($ReturnDataVO->result) <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = "Senha não Confere";
				$ReturnDataVO->code_return = -1;
			}
			
			return $ReturnDataVO;	
		}
		
		public function checkLogin($login = NULL, $password = NULL, $id = NULL){
			$ReturnDataVO = new ReturnDataVO();
			//echo $password." pass enviado";
			$this->getUserDAO();
			if($id > 0 && $password !== NULL){
				$ReturnDataVO = self::$_userDAO->select(UserDAO::RETURN_STD_OBJECT, $id, 1, NULL, NULL, $password, NULL, 1);
				if(!$ReturnDataVO->success || count($ReturnDataVO->result) <= 0){
					$ReturnDataVO->success = FALSE;
					$ReturnDataVO->result = "Senha não Confere";
					$ReturnDataVO->code_return = -1;
				}
			}else if($login !== NULL || $password !== NULL){
				$ReturnDataVO = self::$_userDAO->select(UserDAO::RETURN_STD_OBJECT, NULL, 1, NULL, $login, $password, NULL, 1);
				//Debug::print_r($ReturnDataVO);
				//exit();
				if(!$ReturnDataVO->success || count($ReturnDataVO->result) <= 0){
					$ReturnDataVO->success = FALSE;
					$ReturnDataVO->result = "Login e Senha não Conferem";
					$ReturnDataVO->code_return = -1;
				}
			}else{
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->result = "Login e Senha não Conferem";
				$ReturnDataVO->code_return = -1;
			}
			return $ReturnDataVO;
		}

		/**
		 * Para inserir precisa 
		 * 
		 * @param $id
		 * @param $address_id
		 * @param $name
		 * @param $last_name
		 * @param $birthday
		 * @param $mobile
		 * @param $telephone
		 * @param $email
		 * @param $rg
		 * @param $cpf
		 * @param $company
		 * @param $website
		 * @param $sex
		 * @param $recive_news
		 * @param $registred
		 * @return ReturnDataVO
		 */
		public function insert(
		 					$id, 
		 					$address_id, 
		 					$name, 
		 					$last_name, 
		 					$birthday, 
		 					$mobile, 
		 					$telephone, 
		 					$email, 
		 					$rg, 
		 					$cpf, 
		 					$company, 
		 					$website, 
		 					$sex, 
		 					$recive_news, 
		 					$registred
  						){

			$query = "INSERT INTO 
						user_details (
		 					`id`, 
		 					`address_id`, 
		 					`name`, 
		 					`last_name`, 
		 					`birthday`, 
		 					`mobile`, 
		 					`telephone`, 
		 					`email`, 
		 					`rg`, 
		 					`cpf`, 
		 					`company`, 
		 					`website`, 
		 					`sex`, 
		 					`recive_news`, 
		 					`registred`
						)VALUES(
							'".DataHandler::forceInt($id)."',
							'".DataHandler::forceInt($address_id)."',
							'".DataHandler::forceString($name)."', 
							'".DataHandler::forceString($last_name)."', 
							".$this->dateHandlerScape(DataHandler::convertDateToDB($birthday)).", 
							'".DataHandler::forceString($mobile)."', 
							'".DataHandler::forceString($telephone)."',
							'".DataHandler::forceString($email)."',  
							'".DataHandler::forceString($rg)."', 
							'".DataHandler::forceString($cpf)."', 
							'".DataHandler::forceString($company)."', 
							'".DataHandler::forceString($website)."', 
							'".DataHandler::forceInt($sex)."', 
							'".DataHandler::forceInt($recive_news)."', 
							NOW()
						)";
			$ReturnDataVO = parent::query($query, TRUE);
			//
			return $ReturnDataVO;
		}
		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function active($id){
			$query = "UPDATE user_details SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		
		/* @$id number
		 * @return class ReturnDataVO 
		 */
		public function deactive($id){
			$query = "UPDATE user_details SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
			$ReturnDataVO = parent::query($query);
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}

		/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function delete($id){
			$this->getUserDAO();
			$tempReturnDataVO = self::$_userDAO->delete($id);
			if($tempReturnDataVO->success){
				$query = "DELETE FROM user_details WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
				$ReturnDataVO = parent::query($query);
			}else{
				$ReturnDataVO = $tempReturnDataVO;
			}
			//Debug::print_r($ReturnDataVO);
			return $ReturnDataVO;
		}
		/**
	     * @param VO
	     * @return class ReturnDataVO 
	     */
		public function updateVO($VO){
			$this->getUserDAO();
			$tempReturnDataVO = self::$_userDAO->updateVO($VO);
			if(!$tempReturnDataVO->success){
				//erro ao atualizar UserVO
				return $tempReturnDataVO;
			}
			//depois atualiza o Partner, se quiser atualizar só partner, utilize update apenas e não updateVO
			return $this->update(
	 					$VO->getId(), 
	 					$VO->address_id, 
	 					$VO->name, 
	 					$VO->last_name, 
	 					$VO->birthday, 
	 					$VO->mobile, 
	 					$VO->telephone, 
	 					$VO->email, 
	 					$VO->rg, 
	 					$VO->cpf, 
	 					$VO->company, 
	 					$VO->website, 
	 					$VO->sex, 
	 					$VO->recive_news, 
	 					$VO->registred		
				);
		}
		/**
		 * @param $id
		 * @param $address_id
		 * @param $name
		 * @param $last_name
		 * @param $birthday
		 * @param $mobile
		 * @param $telephone
		 * @param $email
		 * @param $rg
		 * @param $cpf
		 * @param $company
		 * @param $website
		 * @param $sex
		 * @param $recive_news
		 * @param $registred
		 * @return ReturnDataVO
		 */
		public function update(
		 					$id, 
		 					$address_id = NULL, 
		 					$name = NULL, 
		 					$last_name = NULL, 
		 					$birthday = NULL, 
		 					$mobile = NULL, 
		 					$telephone = NULL, 
		 					$email = NULL, 
		 					$rg = NULL, 
		 					$cpf = NULL, 
		 					$company = NULL, 
		 					$website = NULL, 
		 					$sex = NULL, 
		 					$recive_news = NULL, 
		 					$registred = NULL
  						){
  			$ReturnDataVO = new ReturnDataVO();
			$query = "UPDATE user_details SET ";
			if($id == NULL && $id <= 0){
				$ReturnDataVO->success = FALSE;
				$ReturnDataVO->code_return = DbInterface::ERROR_UPDATE_DONOT_HAVE_ID;
				return $ReturnDataVO;
			}
			$comma = "";
			if($name !== NULL){
				$query .= $comma." `name` = '".DataHandler::forceString($name)."'";
				$comma = ", ";
			}
			if($address_id > 0){
				$query .= $comma." `address_id` = '".DataHandler::forceInt($address_id)."'";
				$comma = ", ";
			}
			if($last_name !== NULL){
				$query .= $comma." `last_name` = '".DataHandler::forceString($last_name)."'";
				$comma = ", ";
			}
			if($birthday !== NULL){
				$query .= $comma." `birthday` = ".$this->dateHandlerScape(DataHandler::convertDateToDB($birthday));
				$comma = ", ";
			}
			if($mobile !== NULL){
				$query .= $comma." `mobile` = '".DataHandler::forceString($mobile)."'";
				$comma = ", ";
			}
			if($telephone !== NULL){
				$query .= $comma." `telephone` = '".DataHandler::forceString($telephone)."'";
				$comma = ", ";
			}
			if($email !== NULL){
				$query .= $comma." `email` = '".DataHandler::forceString($email)."'";
				$comma = ", ";
			}
			if($rg !== NULL){
				$query .= $comma." `rg` = '".DataHandler::forceString($rg)."'";
				$comma = ", ";
			} 
			if($cpf !== NULL){
				$query .= $comma." `cpf` = '".DataHandler::forceString($cpf)."'";
				$comma = ", ";
			} 
			if($company !== NULL){
				$query .= $comma." `company` = '".DataHandler::forceString($company)."'";
				$comma = ", ";
			}
			if($website !== NULL){
				$query .= $comma." `website` = '".DataHandler::forceString($website)."'";
				$comma = ", ";
			}
			if($sex !== NULL){
				$query .= $comma." `sex` = '".DataHandler::forceInt($sex)."'";
				$comma = ", ";
			}
			if($recive_news !== NULL){
				$query .= $comma." `recive_news` = '".DataHandler::forceInt($recive_news)."'";
				$comma = ", ";
			}
			if($registred !== NULL){
				//$query .= $comma." `registred` = ".$this->dateHandlerScape($registred);
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
		public function select(
								$typeOfReturn = NULL, 
			 					$id = NULL, 
			 					$address_id = NULL, 
			 					$name = NULL, 
			 					$last_name = NULL, 
			 					$birthday = NULL, 
			 					$mobile = NULL, 
			 					$telephone = NULL, 
			 					$email = NULL, 
			 					$rg = NULL, 
			 					$cpf = NULL, 
			 					$company = NULL, 
			 					$website = NULL, 
			 					$sex = NULL, 
			 					$recive_news = NULL, 
			 					$registred = NULL,
								$quant_limit = NULL, 
								$quant_started = NULL,
								$user_type_id = NULL,
								$date = NULL,
								$date_symbol = ">="
							){
			$arrayFilter = array();
			$query = "SELECT 
							user.id as user_id,
							user.active,
							user.user_type_id,
							user.login,
							user.password,
							user.email as user_email, 
							user_details.*
						FROM 
					  		user_details
					  	INNER JOIN 
					  		user ON user.id = user_details.id
					  WHERE 1 ";
			$this->addFilter(
								$query,
								$id, 
			 					$address_id, 
			 					$name, 
			 					$last_name, 
			 					$birthday, 
			 					$mobile, 
			 					$telephone, 
			 					$email, 
			 					$rg, 
			 					$cpf, 
			 					$company, 
			 					$website, 
			 					$sex, 
			 					$recive_news, 
			 					$registred,
			 					$user_type_id,
								$date,
								$date_symbol
							);
			
			$queryCount = "
						SELECT 
							COUNT(*) AS count
						FROM 
					  		user_details
					  	INNER JOIN 
					  		user ON user.id = user_details.id
					  WHERE 1 
	  	  					";
			$this->addFilter(
								$queryCount,
								$id, 
			 					$address_id, 
			 					$name, 
			 					$last_name, 
			 					$birthday, 
			 					$mobile, 
			 					$telephone, 
			 					$email, 
			 					$rg, 
			 					$cpf, 
			 					$company, 
			 					$website, 
			 					$sex, 
			 					$recive_news, 
			 					$registred,
			 					$user_type_id,
								$date,
								$date_symbol
			 					);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
//			echo $query;
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
		/**
		 * @param $typeOfReturn
		 * @param $name
		 * @param $mobile
		 * @param $telephone
		 * @param $email
		 * @param $rg
		 * @param $cpf
		 * @param $company
		 * @param $quant_limit
		 * @param $quant_started
		 * @param $user_type_id
		 * @return ReturnDataVO
		 */
		public function search(
								$typeOfReturn = NULL, 
			 					$name = NULL, 
			 					$mobile = NULL, 
			 					$telephone = NULL, 
			 					$email = NULL, 
			 					$rg = NULL, 
			 					$cpf = NULL, 
			 					$company = NULL, 
			 					$quant_limit = NULL, 
								$quant_started = NULL,
								$user_type_id = NULL,
								$recive_news = NULL
							){
			$arrayFilter = array();
			$query = "SELECT 
							user.id as user_id,
							user.active,
							user.user_type_id,
							user.login,
							user.password,
							user.email as user_email, 
							user_details.*
						FROM 
					  		user_details
					  	INNER JOIN 
					  		user ON user.id = user_details.id
					  WHERE 1 ";
			$this->addFilterToSearch(
								$query,
								$name, 
			 					$mobile, 
			 					$telephone, 
			 					$email, 
			 					$rg, 
			 					$cpf, 
			 					$company, 
			 					$user_type_id,
								$recive_news
			 					);
			
			$queryCount = "
						SELECT 
							COUNT(*) AS count
						FROM 
					  		user_details
					  	INNER JOIN 
					  		user ON user.id = user_details.id
					  WHERE 1 
	  	  					";
			$this->addFilterToSearch(
								$queryCount,
								$name, 
			 					$mobile, 
			 					$telephone, 
			 					$email, 
			 					$rg, 
			 					$cpf, 
			 					$company, 
			 					$user_type_id,
			 					$recive_news
							);
			
			//limite
			$query .= parent::limit($quant_limit, $quant_started);
//			echo $query;
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
		private function addFilterToSearch(&$query,
								$name = NULL, 
			 					$mobile = NULL, 
			 					$telephone = NULL, 
			 					$email = NULL, 
			 					$rg = NULL, 
			 					$cpf = NULL, 
			 					$company = NULL, 
			 					$user_type_id = NULL,
			 					$recive_news = NULL){
			if($name !== NULL){
				$query .= " AND user_details.name LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $name)."%'";
			}
			if($mobile !== NULL){
				$query .= " AND user_details.mobile LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $mobile)."%'";
			}
			if($telephone !== NULL){
				$query .= " AND user_details.telephone LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $telephone)."%'";
			}
			if($email !== NULL){
				$query .= " AND (
									user.email LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $email)."%' OR
									user.login LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $email)."%' OR
									user_details.email LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $email)."%'  
								) ";
			}
			if($rg !== NULL){
				$query .= " AND rg LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $rg)."%'";
			}
			if($cpf !== NULL){
				$query .= " AND cpf LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $cpf)."%'";
			}
			if($company !== NULL){
				$query .= " AND company LIKE '%".str_replace(array(" ", "'"), array("%", "%"), $company)."%'";
			}
			if($user_type_id != NULL){
				$query .= " AND `user_type_id` =".DataHandler::forceInt($user_type_id)."";
			}
			if($recive_news != NULL){
				$query .= " AND `recive_news` =".DataHandler::forceInt($recive_news)."";
			}
			 					}
		private function addFilter(
								&$query,
								$id = NULL, 
			 					$address_id = NULL, 
			 					$name = NULL, 
			 					$last_name = NULL, 
			 					$birthday = NULL, 
			 					$mobile = NULL, 
			 					$telephone = NULL, 
			 					$email = NULL, 
			 					$rg = NULL, 
			 					$cpf = NULL, 
			 					$company = NULL, 
			 					$website = NULL, 
			 					$sex = NULL, 
			 					$recive_news = NULL, 
			 					$registred = NULL,
			 					$user_type_id = NULL,
								$date = NULL,
								$date_symbol = ">="
			 					){
			if($id !== NULL){
				$query .= " AND user.id = '".DataHandler::forceInt($id)."'";
			}
			if($name !== NULL){
				$query .= " AND `name` LIKE '%".DataHandler::forceString($name)."%'";
			}
			if($last_name !== NULL){
				$query .= " AND `last_name` ='".DataHandler::forceString($last_name)."'";
			}
			if($birthday !== NULL){
				$query .= " AND `birthday` =".$this->dateHandlerScape($birthday);
			}
			if($mobile !== NULL){
				$query .= " AND `mobile` ='".DataHandler::forceString($mobile)."'";
			}
			if($telephone !== NULL){
				$query .= " AND `telephone` ='".DataHandler::forceString($telephone)."'";
			}
			if($email !== NULL){
				$query .= " AND `email` ='".DataHandler::forceString($email)."'";
			}
			if($rg !== NULL){
				$query .= " AND `rg` ='".DataHandler::forceString($rg)."'";
			}
			if($cpf !== NULL){
				$query .= " AND `cpf` ='".DataHandler::forceString($cpf)."'";
			}
			if($company !== NULL){
				$query .= " AND `company` ='".DataHandler::forceString($company)."'";
			}
			if($website !== NULL){
				$query .= " AND `website` ='".DataHandler::forceString($website)."'";
			}
			if($sex !== NULL){
				$query .= " AND `sex` ='".DataHandler::forceInt($sex)."'";
			}
			if($recive_news !== NULL){
				$query .= " AND `recive_news` ='".DataHandler::forceInt($recive_news)."'";
			}
			if($registred !== NULL){
				$query .= " AND `registred` =".$this->dateHandlerScape($registred)."";
			}
			if($user_type_id != NULL){
				$query .= " AND `user_type_id` =".DataHandler::forceInt($user_type_id)."";
			}
			if($date !== NULL){
				$date_symbol = parent::compareSimble($date_symbol);
				$date = parent::dateHandlerScape(DataHandler::convertDateToDB($date));
				$query .= " AND `registred` $date_symbol $date ";
			}
}
		
		public function getVO(){
			return new UserDetailVO();
		}
		/**
		 * @return UserDAO
		 */
		public function getUserDAO(){
			if(!self::$_userDAO){
				self::$_userDAO = new UserDAO();
			}
			return self::$_userDAO;
		}
}