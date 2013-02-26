<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(Config::FOLDER_APPLICATION."modules/newsletter/dao/NewsletterDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/user_detail/dao/UserDetailDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";

include_once(Config::FOLDER_APPLICATION."controller/Admin.php");
class Newsletter extends Admin implements HTTPControllerInterface{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();

	private $DAO;

	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function init(){
		$email_search = NULL;
		if(DataHandler::getValueByArrayIndex($_POST, "email_search")){
			//tem busca, vai tratar essa variavel
			$email_search = DataHandler::getValueByArrayIndex($_POST, "email_search");
		}
		
		$date = NULL;
		if(DataHandler::getValueByArrayIndex($_POST, "date")){
			//tem busca, vai tratar essa variavel
			$date = DataHandler::getValueByArrayIndex($_POST, "date");
		}
		
		$date_filter = NULL;
		if(DataHandler::getValueByArrayIndex($_POST, "date_filter")){
			//tem busca, vai tratar essa variavel
			$date_filter = DataHandler::getValueByArrayIndex($_POST, "date_filter");
			if($date_filter == '1'){
				$date_filter = ">=";
			}else if($date_filter == '1'){
				$date_filter = "==";
			}else if($date_filter == '-1'){
				$date_filter = "<=";
			}else{
				$date_filter = NULL;
			}
		}
		
		
		$page = 1;
		$limit_page = 21;
		$quant_start = 0;
		
		foreach($this->arrayVariable as $variable => $value){
			if($variable == "pag" && DataHandler::forceInt($value) > 0){
				$page = DataHandler::forceInt($value);
			}
		}
		
		$quant_start = $page * $limit_page - $limit_page;
		$this->NewsletterDAO = NewsletterDAO::getInstance();
		
		if(false){
			$this->NewsletterDAO = new NewsletterDAO();
			$UserDetailDAO = new UserDetailDAO();
		}
		$UserDetailDAO = UserDetailDAO::getInstance();
		
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();

//		echo Debug::li("data".$date_filter);
		if($email_search != NULL){
			//fazendo o que precisa fazer para ter os dados
			$ReturnDataUserVO = $UserDetailDAO->search(UserDetailDAO::RETURN_STD_OBJECT, NULL, NULL, NULL, $email_search, NULL, NULL, NULL, NULL, NULL, NULL, 1);
			if($ReturnDataUserVO->success){
	//			Debug::print_r($ReturnDataUserVO);
				$tempCount = $ReturnDataUserVO->count_total;
			}
			//		exit();
		}else{
			//fazendo o que precisa fazer para ter os dados
			$ReturnDataUserVO = $UserDetailDAO->select(UserDetailDAO::RETURN_STD_OBJECT, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, $date, $date_filter);
			if($ReturnDataUserVO->success){
	//			Debug::print_r($ReturnDataUserVO);
				$tempCount = $ReturnDataUserVO->count_total;
			}
		}
		$ReturnDataNewsletterVO = $this->NewsletterDAO->select(NewsletterDAO::RETURN_STD_OBJECT, NULL, 1, $email_search, NULL, NULL, $date, $date_filter);	
		if($ReturnDataNewsletterVO->success){
//			Debug::print_r($ReturnDataNewsletterVO);
			$tempCount = $tempCount + $ReturnDataNewsletterVO->count_total;
		}
		
//		Debug::print_r($ReturnDataVO);
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess(TRUE);
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->arrayNewsletter  = $ReturnDataNewsletterVO->result;
		$retornoDaPaginaHTML->arrayUser  = $ReturnDataUserVO->result;
		$retornoDaPaginaHTML->page = $page;
		$retornoDaPaginaHTML->limit_page = $limit_page;
		$retornoDaPaginaHTML->count_total = $tempCount;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	public function delete(){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = FALSE;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");
				if(DataHandler::getValueByArrayIndex($this->arrayVariable, "deactive")){
				$UserDetailDAO = UserDetailDAO::getInstance();
				$UserDetailVO = $UserDetailDAO->getVO();
				$UserDetailVO->setId($id, TRUE);
				$UserDetailVO->recive_news = 0;
				$ReturnResultVO = $UserDetailVO->commit();
			}else{
				$this->NewsletterDAO = NewsletterDAO::getInstance();
				$ReturnDataVO = $this->NewsletterDAO->deactive($id);
				$ReturnResultVO->success	 	= $ReturnDataVO->success;
				$ReturnResultVO->result			= $ReturnDataVO->result;
			}
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
	
	public function excel(){
		$this->NewsletterDAO = NewsletterDAO::getInstance();
		if(false){
			$this->NewsletterDAO = new NewsletterDAO();
			$UserDetailDAO = new UserDetailDAO();
		}
		$UserDetailDAO = UserDetailDAO::getInstance();
		
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();

		//fazendo o que precisa fazer para ter os dados
		$ReturnDataUserVO = $UserDetailDAO->select(UserDetailDAO::RETURN_STD_OBJECT, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);
		if($ReturnDataUserVO->success){
//			Debug::print_r($ReturnDataUserVO);
			$tempCount = $ReturnDataUserVO->count_total;
		}
		$ReturnDataNewsletterVO = $this->NewsletterDAO->select(NewsletterDAO::RETURN_STD_OBJECT, NULL, 1, NULL);	
		if($ReturnDataNewsletterVO->success){
//			Debug::print_r($ReturnDataNewsletterVO);
			$tempCount = $tempCount + $ReturnDataNewsletterVO->count_total;
		}
		

//Debug::print_r($ReturnDataVO);
		$html[] = "<table><tr><td>email</td><td>data</td></tr></table>";
				
		foreach($ReturnDataNewsletterVO->result as $newsVO){
		    $html[] = "<table><tr><td>$newsVO->email</td><td>$newsVO->date</td></tr></table>";
		}
		foreach($ReturnDataUserVO->result as $userVO){
		    $html[] = "<table><tr><td>$userVO->email</td><td>$userVO->registred</td></tr></table>";
		}
		
		$arquivo = Config::getRootPath("view/newsletter/excel.xls");
			
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename={$arquivo}" );
		header ("Content-Description: PHP Generated Data" );
		 
		for($i=0;$i<=count($html);$i++){
			if(isset($html[$i]))  
		    	echo $html[$i];
		}
		exit();
	}

}