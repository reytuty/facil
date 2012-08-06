<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
class Info implements HTTPControllerInterface{
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
		//id das demais categorias que nao vao ser agrupadas
		$category_id = 86;
		if(count($this->arrayRestFolder)>1){
			$slug_category = $this->arrayRestFolder[1];
			$category_id = ($this->arrayRestFolder[0] == "segmento")?125:126;
		} else {
			$slug_category = $this->arrayRestFolder[0];
		}
		
		$ContentDAO = ContentDAO::getInstance();
		$ReturnDataVO = $ContentDAO->selectBySlugAndTableId($slug_category, "category", $category_id, NULL, ContentDAO::RETURN_VO);
//		"category", $ReturnDataVO->result[0]->id, ContentDAO::RETURN_VO);
		
		//echo Debug::print_r($ReturnDataVO);exit();
		//iniciando o retorno padrao em http result
		
		//fazendo o que precisa fazer para ter os dados
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		
		//Debug::print_r($retornoDaPaginaHTML);
		$retornoDaPaginaHTML->content_module = (count($ReturnDataVO->result) > 0)?$ReturnDataVO->result[0]:NULL;
		if($retornoDaPaginaHTML->content_module){
			$contentSiteVO = new ContentSiteVO();
			$contentSiteVO->setId($retornoDaPaginaHTML->content_module->id, TRUE);
			$retornoDaPaginaHTML->content_module = $contentSiteVO;
		}
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		
		$retornoDaPaginaHTML->array_segment = $this->getContents(125);
		
		$retornoDaPaginaHTML->array_estrutura = $this->getContents(126);
		
		//Debug::print_r($retornoDaPaginaHTML);
		//salvando o objeto de resultado de html no retorno
			
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		
		return $returnResult;
	}
	private function getContents($category_id){
		$LinkDAO = LinkDAO::getInstance();
		$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $category_id, "content", NULL, 1,NULL, NULL);
			//verifica se o resultado é uma categoryVO
			$arrayContentsVO = array();
			if($returnDataVO->success && count($returnDataVO->result)>0){
				foreach($returnDataVO->result as $LinkVO){
					//Debug::print_r($LinkVO);
					$tempReturnDataVO = $LinkVO->getLinkedVO();
					//Debug::print_r($tempReturnDataVO);exit();
					if($tempReturnDataVO->success){
						if($tempReturnDataVO->result->active >= 0){
							$arrayContentsVO[] = $tempReturnDataVO->result;
						}//end if active
					}
				}
				//exit();
			}
			return $arrayContentsVO;
	}
}