<?php

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");

include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class RootController{
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	private $DAO;
	
	public function __construct($arrayRestFolder){
		Config::getConection();
		$this->DAO = CategoryDAO::getInstance(); 
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
			//$this->MenuController = new MenuController($this->arrayVariable);
		}
	}
	public function init(){
		//busca todas as paginas cadastradas na tabela content
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
		$retornoDaPaginaHTML->produtos = array();
		//verifica se não está acessando outra página que não a home
		if(count($this->arrayRestFolder) == 0){
			$DAO = ContentSiteDAO::getInstance();
			$ResultDAO = $DAO->select(		ContentSiteDAO::RETURN_VO, 
								$id = NULL, 
								$active = 1, 
								$name = NULL,
								$title = NULL,
								$hat = NULL,
								$description = NULL,
								$content = NULL,
								$author = "1",
								$template_url = NULL,
								$slug = NULL,
								$key_words = NULL);
			$ResultSegmentoDAO = $DAO->select(		ContentSiteDAO::RETURN_VO, 
								$id = NULL, 
								$active = 2, 
								$name = NULL,
								$title = NULL,
								$hat = NULL,
								$description = NULL,
								$content = NULL,
								$author = "1",
								$template_url = NULL,
								$slug = NULL,
								$key_words = NULL);
			
			$array_produtos = array();
			foreach($ResultDAO->result as $vo){
				//Debug::print_r($vo);
				$stdProduct = $vo->toStdClass();
				if($stdProduct->active > 0){
					$stdProduct->array_tagged		= $vo->getImages(NULL, "tagged", true);
					//só entram na lista produtos em que estejam na categoria produtos, isso evita trazer outros contents
					$arrayDads = $vo->getCategoriesDad();
					if(in_array(2, $arrayDads)){
						$array_produtos[] = $stdProduct;
					}
				}
			}
			foreach($ResultSegmentoDAO->result as $vo){
				//Debug::print_r($vo);
				$stdProduct = $vo->toStdClass();
				if($stdProduct->active > 0){
					$stdProduct->array_tagged		= $vo->getImages(NULL, "tagged", true);
					//só entram na lista produtos em que estejam na categoria produtos, isso evita trazer outros contents
					$arrayDads = $vo->getCategoriesDad();
					if(in_array(2, $arrayDads)){
						$array_produtos[] = $stdProduct;
					}
				}
			}
			shuffle($array_produtos);
			$retornoDaPaginaHTML->produtos = $array_produtos;
		}
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
}