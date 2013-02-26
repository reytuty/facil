<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class ContentModule implements HTTPControllerInterface{
	private $arrayRestFolder;
	private $arrayVariable;
	private $DAO;

	public function __construct($arrayRestFolder = NULL){
		$this->DAO = new CategoryDAO(); 
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/* (non-PHPdoc)
	 * @see library/facil3/core/controller/interface/HTTPControllerInterface#init()
	 */
	public function init(){
		//busca todas as paginas cadastradas na tabela content
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpContentModule();
		$slug_category = "404"; 
		
		if(count($this->arrayRestFolder)>0){
			$slug_category = $this->arrayRestFolder[0];
		}
		
		

		$ContentDAO = ContentDAO::getInstance();
		$ReturnDataVO = $ContentDAO->selectBySlugAndTableId($this->arrayRestFolder[0], "category", "18", NULL, ContentDAO::RETURN_VO);
//		"category", $ReturnDataVO->result[0]->id, ContentDAO::RETURN_VO);
//		echo Debug::print_r($ReturnDataVO);
		//iniciando o retorno padrao em http result
		
		//fazendo o que precisa fazer para ter os dados
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		
		//Debug::print_r($retornoDaPaginaHTML);
		$retornoDaPaginaHTML->content_module = $ReturnDataVO->result[0];
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->setFetchArray($ReturnDataVO->result);
		//Debug::print_r($retornoDaPaginaHTML);
		//salvando o objeto de resultado de html no retorno
			
			
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	/* (non-PHPdoc)
	 * @see library/facil3/core/controller/interface/HTTPControllerInterface#init()
	 */
	public function contato(){
		//busca todas as paginas cadastradas na tabela content
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpContentModule();
		$slug_category = "404"; 
		
		if(count($this->arrayRestFolder)>0){
			$slug_category = $this->arrayRestFolder[0];
		}
		
		
		$ContentDAO = ContentDAO::getInstance();
		
		$ReturnDataVO = $ContentDAO->selectByTableId("category", $ReturnDataVO->result[0]->id, ContentDAO::RETURN_VO);
//		echo Debug::print_r($ReturnDataVO);
		//iniciando o retorno padrao em http result
		
		//fazendo o que precisa fazer para ter os dados
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		
		//Debug::print_r($retornoDaPaginaHTML);
		$retornoDaPaginaHTML->content_module = $ReturnDataVO->result;
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->setFetchArray($ReturnDataVO->result);
		//Debug::print_r($retornoDaPaginaHTML);
		//salvando o objeto de resultado de html no retorno

		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
}