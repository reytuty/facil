<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @desc		: usa as tabelas user, user detail e user_address 
	 * 
	 * 			tabela user_detail:
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

//importa classes de apoio
include_once "library/facil3/core/modules/user_address/dao/UserAddressDAO.class.php";
include_once "library/facil3/core/modules/user/dao/UserDAO.class.php";
include_once Config::FOLDER_APPLICATION."/modules/user_detail/dao/UserDetailDAO.class.php";
include_once Config::FOLDER_APPLICATION."/modules/user_detail/vo/UserDetailVO.class.php";

/**
 * @author		: Mauricio Amorim
 * @date		: 15/07/2010
 * @version		: 1.0
 * @desc		: 
 * 
 */
class UserDetailVO extends UserVO {
	/*
  	 * abaixo ele herda
  	public $user_type_id;
  	public $login;
  	public $password;
  	public $email; 
  	*/
	//campos da tabela detalhe do usario (user_detail)
	public $address_id;
 	public $name;
 	public $last_name;
 	public $birthday;
 	public $mobile;
 	public $telephone;
 	public $email;
 	public $rg;
 	public $cpf;
 	public $company;
 	public $website;
 	public $sex;
 	public $recive_news;
 	public $registred;
 	
 	public $array_address_std;
 	
	public static $UserAddressDAO;
	 					
	function __construct($arrayFetchPost = NULL){
		$this->setFetchArray($arrayFetchPost);
		//passa para a BaseVo que a tabela a ser utilizada é a product_model_enc_quadro(atributos do modelo de produto)
		$this->__table = "user_detail";
	}
	/**
	 * inicia interloginnte a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = UserDetailDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padrões dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 * @desc   esse metodo pega tanto os valores dos atributos do modelo quanto do modelo do produto
	 */
	function setFetchArray($array_dados){
		//faz o fetch dos campos da tabela user (usuario)
		parent::setFetchArray($array_dados);
		
		//campos da tabela user_detail (detalhe do usuario)

		$this->address_id 	= DataHandler::getValueByArrayIndex($array_dados, "address_id");
	 	$this->name 		= DataHandler::getValueByArrayIndex($array_dados, "name");
	 	$this->last_name 	= DataHandler::getValueByArrayIndex($array_dados, "last_name");
	 	$this->birthday 	= DataHandler::getValueByArrayIndex($array_dados, "birthday");
	 	$this->mobile 		= DataHandler::getValueByArrayIndex($array_dados, "mobile");
	 	$this->telephone 	= DataHandler::getValueByArrayIndex($array_dados, "telephone");
	 	$this->email 		= DataHandler::getValueByArrayIndex($array_dados, "email");
	 	$this->login 		= DataHandler::getValueByArrayIndex($array_dados, "email");
	 	$this->rg 			= DataHandler::getValueByArrayIndex($array_dados, "rg");
	 	$this->cpf 			= DataHandler::getValueByArrayIndex($array_dados, "cpf");
	 	$this->company 		= DataHandler::getValueByArrayIndex($array_dados, "company");
	 	$this->website 		= DataHandler::getValueByArrayIndex($array_dados, "website");
	 	$this->sex 			= DataHandler::getValueByArrayIndex($array_dados, "sex");
	 	$this->recive_news 	= DataHandler::getValueByArrayIndex($array_dados, "recive_news");
	 	$this->registred 	= DataHandler::getValueByArrayIndex($array_dados, "registred");
		
	}

	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		//faz o fetch dos campos da tabela product_model (modelo do produto)
		parent::setFetchObject($obj_dados);
		
		//campos da tabela user_detail (detalhe do usuario)
		$this->address_id = $obj_dados->address_id;
	 	$this->name = $obj_dados->name;
	 	$this->last_name = $obj_dados->last_name;
	 	$this->birthday = $obj_dados->birthday;
	 	$this->mobile = $obj_dados->mobile;
	 	$this->telephone = $obj_dados->telephone;
	 	$this->rg = $obj_dados->rg;
	 	$this->cpf = $obj_dados->cpf;
	 	$this->company = $obj_dados->company;
	 	$this->website = $obj_dados->website;
	 	$this->sex = $obj_dados->sex;
	 	$this->recive_news = $obj_dados->recive_news;
	 	$this->registred = $obj_dados->registred;
		
	}
	//---------------------------------------------------------  SETs

	/**
	 * @param address_id (int)
	 */
	public function setAddressId($address_id){
		$this->address_id = DataHandler::forceInt($address_id);
	}
	/**
	 * @param email (str)
	 */
	public function setLogin($email){
		$this->email = DataHandler::forceString($email);
	}
	/**
	 * @param name (str)
	 */
	public function setName($name){
		$this->name = DataHandler::forceString($name);
	}

	/**
	 * @param last_name (str)
	 */
	public function setLastName($last_name){
		$this->last_name = DataHandler::forceString($last_name);
	}
	
	/**
	 * @param birthday (date)
	 */
	public function setBirthday($birthday){
		$this->birthday = DataHandler::convertDateToDB($birthday);
	}
	
	/**
	 * @param mobile (str)
	 */
	public function setMobile($mobile){
		$this->mobile = DataHandler::forceString($mobile);
	}
	
	/**
	 * @param telephone (str)
	 */
	public function setTelephone($telephone){
		$this->telephone = DataHandler::forceString($telephone);
	}
	
	/**
	 * @param email (str)
	 */
	public function setEmail($email){
		$this->email = DataHandler::forceString($email);
	}
	
	/**
	 * @param rg (str)
	 */
	public function setRg($rg){
		$this->rg = DataHandler::forceString($rg);
	}
	
	/**
	 * @param cpf (str)
	 */
	public function setCpf($cpf){
		$this->cpf = DataHandler::forceString($cpf);
	}
	
	/**
	 * @param company (str)
	 */
	public function setCompany($company){
		$this->company = DataHandler::forceString($company);
	}
	/**
	 * @param website (str)
	 */
	public function setWebsite($website){
		$this->website = DataHandler::forceString($website);
	}
	/**
	 * @param sex (int)
	 */
	public function setSex($sex){
		if($sex !== NULL){
			$this->sex = DataHandler::forceInt($sex);
		}
	}
	/**
	 * @param recive_news (int)
	 */
	public function setReciveNews($recive_news){
		if($recive_news !== NULL){
			$this->recive_news = DataHandler::forceInt($recive_news);
		}
	}
	/**
	 * @param registred (date)
	 */
	public function setRegistred($registred){
		$this->registred = DataHandler::convertDateToDB($registred);
	}
	// ------------------------------------------------------   GETs

	/**
	 * @return address_id (int)
	 */
	public function getAddressId(){
		return $this->address_id;
	}

	/**
	 * @return name (str)
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return name (str)
	 */
	public function getLogin(){
		return $this->email;
	}
	
	/**
	 * @return last_name (str)
	 */
	public function getLastName(){
		return $this->last_name;
	}
	
	/**
	 * @return birthday (date)
	 */
	public function getBirthday(){
		return $this->birthday;
	}
	
	/**
	 * @return mobile (str)
	 */
	public function getMobile(){
		return $this->mobile;
	}
	
	/**
	 * @return website (str)
	 */
	public function getWebsite(){
		return $this->website;
	}
	
	/**
	 * @return telephone (str)
	 */
	public function getTelephone(){
		return $this->telephone;
	}
	
	/**
	 * @return email (str)
	 */
	public function getEmail(){
		return $this->email;
	}
	
	/**
	 * @return rg (str)
	 */
	public function getRg(){
		return $this->rg;
	}
	
	/**
	 * @return cpf (str)
	 */
	public function getCpf(){
		return $this->cpf;
	}
	
	/**
	 * @return company (str)
	 */
	public function getCompany(){
		return $this->company;
	}
	/**
	 * @return sex (int)
	 */
	public function getSex(){
		return $this->sex;
	}
	/**
	 * @return recive_news (int)
	 */
	public function getReciveNews(){
		return $this->recive_news;
	}
	/**
	 * @return registred (date)
	 */
	public function getRegistred(){
		return DataHandler::convertDateToBrazil($this->registred);
	}
	
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		//o login é um email por isso deve ser validado como email
		if(!Validation::blank($this->getLastName(), 1)){
			//não tem mais de 1 nome, precisa de sobrenome
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("preencha seu sobrenome");
		}
		if(!Validation::blank($this->getName(), 1)){
			//não tem mais de 1 nome, precisa de sobrenome
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("preencha seu nome");
		}
		if(!Validation::blank($this->getPassword(), 4)){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("digite uma senha"));
		}
		if(!Validation::validateEmail($this->getEmail())){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("E-mail invalido"));
		}
		return $ReturnResultVO;
	}
	
	//busca endereços cadastrados 
	public function getAddress($force = false){
		if(self::$UserAddressDAO == NULL){
			self::$UserAddressDAO = new UserAddressDAO();
		}
		if($this->array_address_std == NULL || $force){
			//busca produtos por ordem de mais barato
			$array_address = self::$UserAddressDAO->selectByUserId(DbInterface::RETURN_STD_OBJECT, $this->id);
			if($array_address->success){
				//print_r($array_address->result);
				foreach($array_address->result as $array_address_std_obj){
					$this->array_address_std[] = $array_address_std_obj;
				}
			}
		}
		return $this->array_address_std;
	}
	
	/**
	 * @param int $tempResult
	 * @param array $arrayReturn
	 */
	public function resultHandler($tempResult, &$arrayReturn){
		switch($tempResult){
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("id?");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("nao tem o que mudar");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("erro ao atualizar");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("erro ao inserir");
				break;
			case DbInterface::ERROR_DUPLICATE_ENTRY;
				$arrayReturn[] = Translation::text("entrada duplicada");
				break;
			case DbInterface::SUCCESS:
				$arrayReturn[] = Translation::text("sucesso ao cadastrar");
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}
	
}