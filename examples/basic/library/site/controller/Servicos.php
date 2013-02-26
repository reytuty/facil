<?php
include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/modules/content/dao/ContentDAO.class.php");
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/content_module/HttpContentModule.class.php"));

class Servicos implements HTTPControllerInterface{
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
		$retornoDaPaginaHTML->content_module = $ReturnDataVO->result[0];
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->setFetchArray($ReturnDataVO->result);
		//Debug::print_r($retornoDaPaginaHTML);
		//salvando o objeto de resultado de html no retorno
		//serviços
			$item1 = $this->getContent(18, 480, 286);
			//como funciona
			$item2 = $this->getContent(19, 480, 286);
			//precauçoes
			$item3 = $this->getContent(20, 480, 286);	
			
			$parte = new stdClass();
			$parte->title = 'Parte 1';
			$parte->x = 300;
			$parte->y = 200;
			$parte->content = "";
			//varre a categoria de partes do homem
			$partes_homem = $this->getAreas(17);
			$retornoDaPaginaHTML->homem = $partes_homem;
			$retornoDaPaginaHTML->mulher = $this->getAreas(18);
			
			$retornoDaPaginaHTML->items = array($item1,$item2,$item3);
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	private function getAreas($category_id){
		$array_result = array();
		$tempArray = $this->getCategoryCascade($category_id);
		//Debug::print_r($tempArray);exit();
		/*
			 * Array
(
    [0] => stdClass Object
        (
            [id] => 48
            [category_id] => 17
            [name] => Rosto
            [slug] => 800|200
            [active] => 1
            [order] => 0
            [__array_category] => Array
                (
                )

            [step] => 1
        )
			 */
		foreach($tempArray as $catStd){
			$desc = $this->getContentByCategoryId($catStd->id);
			$parte = $this->getStdParte($catStd->name, $catStd->slug, $desc);
			$array_result[] = $parte;
		}
		return $array_result;
	}

	private function getCategoryCascade($dad_category_id){
		$CategoryVO = new CategoryVO();
		$CategoryVO->setId($dad_category_id, TRUE);
		return $CategoryVO->selectCascade(CategoryDAO::RETURN_STD_OBJECT, 1);;
	}
	private function getStdParte($title, $xy, $content = ""){
		$parte = new stdClass();
		$parte->title = $title;
		$array = explode("|", $xy);
		if(is_array($array) && count($array) > 0){
			$parte->x = $array[0];
			$parte->y = $array[1];
		} else {
			$parte->x = 0;//deu errado, poe 0,0
			$parte->y = 0;
		}
		$parte->content = $content;
		return $parte;
	}
	protected function getContentByCategoryId($category_id){
		//pega a CategoryVO	
		$CategoryVO = new CategoryVO();
		$CategoryVO->setId($category_id, TRUE);
		$conteudos = $CategoryVO->getLinks("content", 1);
		if(is_array($conteudos) && count($conteudos) > 0){
			$Link = $conteudos[0];
			$ContentVO = new ContentVO();
			$ContentVO->setId($Link->linked_table_id, TRUE);
			return $ContentVO->getContent();
			//Debug::print_r($ContentVO);
		}
		return "";
	}
	protected function getContent($id, $width = "400", $height = "400"){
		//pega a CategoryVO	
		
			$ContentVO = new ContentSiteVO();
			$ContentVO->setId($id, TRUE);
			$images = $ContentVO->getImages();
			
			$stdResult = new stdClass();
			$stdResult->id 			= $ContentVO->getId();
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