<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class Sobre implements HTTPControllerInterface{
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
		//iniciando o retorno padrao em http result
		//fazendo o que precisa fazer para ter os dados
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		
		//Debug::print_r($retornoDaPaginaHTML);
		//$retornoDaPaginaHTML->content_module = $ReturnDataVO->result[0];
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->setFetchArray($ReturnDataVO->result);
		//Debug::print_r($retornoDaPaginaHTML);
		//salvando o objeto de resultado de html no retorno
		
			$item1 = $this->getContent(16, 480, 287);
			//forçando a barra
			$item1->image_url = array('http://www.espacolaser.com.br/carta-ygor.jpg');
			
			$item2 = $this->getContent(15, 480, 287);
			
			$item3 = $this->getContent(17, 480, 287);	
			
			$retornoDaPaginaHTML->items = array($item1,$item2,$item3);
			
			
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	protected function getContent($id, $width = "400", $height = "400"){
		//pega a CategoryVO	
		
			$ContentVO = new ContentSiteVO();
			$ContentVO->setId($id, TRUE);
			$images = $ContentVO->getImages();
			
			$stdResult = new stdClass();
			$stdResult->title 		= $ContentVO->getTitle();
			$stdResult->content 	= $ContentVO->getContent();
			$stdResult->image_url 	= array();
			
			if(count($images)> 0){
				foreach($images as $image){
					$url = Config::getRootPath("/image/get_image/image_id.".$image->id."/max_width.$width/max_height.$height/crop.1/");//é o link
					$stdResult->image_url[] = $url;
				}
			}
		
		return $stdResult;
	}
}