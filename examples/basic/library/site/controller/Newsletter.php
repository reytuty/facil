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
class Newsletter{
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	private $DAO;
	
	public function __construct($arrayRestFolder){
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
	}
	/**
	 * para detalhe de um produto
	 */
	public function init(){
		//echo 12;
		exit();
	}
	public function add(){
		//name
		$nome 		= DataHandler::getValueByArrayIndex($_POST, "nome");
		//title
		$sobreNome 	= DataHandler::getValueByArrayIndex($_POST, "sobreNome");
		//author
		$email 		= DataHandler::getValueByArrayIndex($_POST, "email");
		//hat
		$companhia 	= DataHandler::getValueByArrayIndex($_POST, "companhia");
		//slug
		$profissao 	= DataHandler::getValueByArrayIndex($_POST, "provissao");
		//key_words
		$arquiteto 	= DataHandler::getValueByArrayIndex($_POST, "arquiteto");
		
		$erros = array();
		//validação
		if(!Validation::validateEmail($email)){
			$erros[] = "email inválido";
		}
		if(!$nome){
			$erros[] = "nome inválido";
		}
		if(!$sobreNome){
			$erros[] = "sobrenome incorreto";
		}
		$ReturnResultVO = new ReturnResultVO($erros);
		if(!count($erros)>0){
			//nao tem erros na validação
			$ContentSiteVO = new ContentSiteVO();
			$ContentSiteVO->setName($nome);
			$ContentSiteVO->setTitle($sobreNome);
			$ContentSiteVO->setAuthor($email);
			$ContentSiteVO->setHat($companhia);
			$ContentSiteVO->setSlug($profissao);
			$ContentSiteVO->setKeyWords($arquiteto);
			$ReturnResultVO = $ContentSiteVO->commit();
			if($ReturnResultVO->success){
				//agora poe esse content na categoria correta
				$CategoryVO = new CategoryVO();
				echo "id de category necessária para newslatter funcionar";exit();
				$CategoryVO->setId(127, TRUE);
				if($ContentSiteVO->getId() > 0){
					$CategoryVO->addLink("newslatter", $ContentSiteVO->getId());
					$CategoryVO->commit();
				}
			}
		}
		echo $ReturnResultVO->toJson();
		exit();
	}
}