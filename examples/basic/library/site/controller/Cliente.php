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
class Cliente{
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
	/**
	 * para detalhe de um produto
	 */
	public function init(){
		//echo 12;
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		if($id > 0){
			$ContentSiteVO = new ContentSiteVO();
			$ReturnResult_vo = $ContentSiteVO->setId($id, TRUE);
			if($ReturnResult_vo->success){
				$stdProduct = $ContentSiteVO->toStdClass();
				$stdProduct->array_gallery 		= $ContentSiteVO->getImages(NULL, "gallery", true);
				$stdProduct->array_dimensions	= $ContentSiteVO->getImages(NULL, "dimensions", true);
				$stdProduct->array_video		= $ContentSiteVO->getImages(NULL, "video", true);
				$stdProduct->array_360			= $ContentSiteVO->getImages(NULL, "360", true);
				$stdProduct->array_tagged		= $ContentSiteVO->getImages(NULL, "tagged", true);
				$LinkDAO = LinkDAO::getInstance();
				//passo 1, descobrir a qual família esse protudo pertence
				//passo 2, pegar todos os produtos pertencente a mesma família
				//passo 3, tirar o próprio produto da listagem de produtos da mesma família
				$array_links = array();
				$array_produtos = array();
				$result_get_link = $ContentSiteVO->getLinks("content", 1);
				//Debug::print_r($result_get_link);
				
				$resultLinks = $LinkDAO->select(DbInterface::RETURN_STD_OBJECT, "content", $table_id=null, $linked_table = 'content', $linked_table_id = $id, $active = 1, $quant_started = NULL, $quant_limit = NULL, $order_by = "order", $order_type = " ASC ");
				foreach($result_get_link as $link){
					//if($link->linked_table_id != $id){
						$ResultTempLink = $link->getLinkedVO();
						if($ResultTempLink->success){
							$produtoVO = $ResultTempLink->result;
							if($produtoVO->active > 0){
								$stdProduto = $produtoVO->toStdClass();
								$stdProduto->array_tagged = $produtoVO->getImages(NULL, "tagged", NULL);
								$array_produtos[] = $stdProduto;
								//Debug::print_r($stdProduto);exit();
							}
						}
					//}//end if
				}//end foerach
				
				if($resultLinks->success && $resultLinks->count_total > 0){
					$link = $resultLinks->result[0];
					$ContentFamiliaVO = new ContentSiteVO();
					$ContentFamiliaVO->setId($link->table_id, TRUE);
					$arrayResult_links = $ContentFamiliaVO->getLinks("content");
					foreach($arrayResult_links as $link){
						if($link->linked_table_id != $id){
							$ResultTempLink = $link->getLinkedVO();
							if($ResultTempLink->success){
								$produtoVO = $ResultTempLink->result;
								if($produtoVO->active > 0){
									$stdProduto = $produtoVO->toStdClass();
									$stdProduto->array_tagged = $produtoVO->getImages(NULL, "tagged", NULL);
									$array_links[] = $stdProduto;
									//Debug::print_r($stdProduto);exit();
								}
							}
						}//end if
					}//end foerach
					//Debug::print_r($array_links);
				}
				//verifica a qual familia esse produto pertence
				$stdProduct->array_produtos_vinculados = $array_links;
				$stdProduct->array_produtos = $array_produtos;
				//Debug::print_r($resultLinks);
				//exit();
				$str_ids_send 		= DataHandler::getValueByArrayIndex($this->arrayVariable, "rel");
				$array_ids_send 	= explode("|", $str_ids_send);
				$array_filtro 		= array();
				foreach($array_ids_send as $id){
					$ContentSiteVO = new ContentSiteVO();
					$tempResult = $ContentSiteVO->setId($id, TRUE);
					if($tempResult->success){
						$stdProduto = $ContentSiteVO->toStdClass();
						$stdProduto->array_tagged = $ContentSiteVO->getImages(NULL, "tagged", NULL);
						$array_filtro[] = $stdProduto;
					}
				}
				$stdProduct->array_filtro = $array_filtro;
				//Debug::print_r($array_links);
				$returnResult = new HttpResult();
				//exit();
				//iniciando o resultado para o html
				$retornoDaPaginaHTML = new HttpRoot();
				$retornoDaPaginaHTML->vo = $stdProduct;
				$retornoDaPaginaHTML->addressToReturn = str_replace("|", "/", DataHandler::getValueByArrayIndex($this->arrayVariable, "filtro"));
				$strToResend = implode("/", $this->arrayRestFolder);
				$strToResend = explode("/:/", $strToResend);
				if(is_array($strToResend) && count($strToResend) > 1){
					$strToResend = $strToResend[1];
				} else {
					$strToResend = "";
				}
				$retornoDaPaginaHTML->addressToResend = $strToResend;
				$returnResult->setHttpContentResult($retornoDaPaginaHTML);
				return $returnResult;
			} else {
				Navigation::redirect("");
			}
		} else {
			//não mandou o id, vai pra listagem
			Navigation::redirect("clientes");
		}
	}
}