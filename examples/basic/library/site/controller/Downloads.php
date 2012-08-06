<?php

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");

include_once("library/facil3/core/modules/file/vo/FileVO.class.php");

include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Downloads{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();
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
	 * 
	 * downloads/file/id.N/
	 */
	public function file(){
		if(!UserClient::getId() > 0){
			Navigation::redirect("405/login_para_download");
			exit();
		}
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		if($id > 0){
			$File = new FileVO();
			$Result = $File->setId($id, TRUE);
			if($Result->success){
				header("Location: ".Config::getRootPath($File->getUrl()));
			} else {
				Navigation::redirect("404/arquivo_nao_encontrado");
			}
		} else {
			Navigation::redirect("405/envie_o_id");
		}
		exit();
	}
	/**
	 * 
	 * downloads/ficha_tecnica/id.N/
	 */
	public function fichaTecnica(){
		if(!UserClient::getId() > 0){
			Navigation::redirect("405/login_para_download");
			exit();
		}
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		if($id > 0){
			$Content = new ContentSiteVO();
			$Result = $Content->setId($id, TRUE);
			
			if($Result->success){
				if(file_get_contents("upload/pdf/$id/".$Content->getSlug().".pdf")){
					header("Location: ".Config::getRootPath("upload/pdf/$id/".$Content->getSlug().".pdf"));
				} else {
					Navigation::redirect("404/pdf_nao_encontrado");
				}
			} else {
				Navigation::redirect("404/arquivo_nao_encontrado");
			}
		} else {
			Navigation::redirect("405/envie_o_id");
		}
		exit();
	}
	/**
	 * 
	 * downloads/jpg/id.N/
	 */
	public function jpg(){
		if(!UserClient::getId() > 0){
			Navigation::redirect("405/login_para_download");
			exit();
		}
		set_time_limit(0);
		$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		if($id > 0){
			$ProductVO = new ContentSiteVO();
			
				//tratando variaveis enviadas
					//a pasta zip precisa existir
					$zip_path = "upload/zip_one/";
					DataHandler::createFolderIfNotExist($zip_path);
					$array_products_to_zip = array();
					$unique_str = "";
					//nome final do zip que vai baixar, adiciona alguns parametros indicadores
					$zip_name = "teto_".$ProductVO->getSlug();
						$resultProduto = $ProductVO->setId($id, TRUE);
						if($resultProduto->success){
							
							$stdProduct = $ProductVO->toStdClass();
							$array_products_to_zip[] = $stdProduct;
							$array_images = array();
							
								
									$array_gallery 		= $ProductVO->getImages(NULL, "gallery", true);
									foreach($array_gallery as $imageVO){
										$array_images[] = $imageVO->getURL();
										$unique_str .= "|".$imageVO->getId();
										//add a imagem na pasta
									}
									//
									$array_dimensions	= $ProductVO->getImages(NULL, "dimensions", true);
									foreach($array_dimensions as $imageVO){
										$array_images[] = $imageVO->getURL();
										//add a imagem na pasta
										$unique_str .= "|".$imageVO->getId();
									}
								
							
							$stdProduct->images = $array_images;
							
							$stdProduct->files 	= null;
							$stdProduct->pdf 	= null;
						} else {
							Navigation::redirect("404/produto_nao_encontrado");
						}//end if produto sucess
					
					$folder_name = md5($unique_str);
					DataHandler::createFolderIfNotExist($zip_path.$folder_name."/");
					$zip_name = $zip_path.$folder_name."/".$zip_name.".zip";
					//echo $zip_name;exit();
					if(!file_exists($zip_name)){
						//echo Debug::li($zip_name);exit();
						$Zip = new ZipArchive();
						$Zip->open($zip_name, ZipArchive::CREATE);
						//adicionando os arquivos escolhidos
						foreach($array_products_to_zip as $ProductStd){
							$product_slug_folder = $ProductStd->id."_".DataHandler::strToURL($ProductStd->title);
							if(count($ProductStd->files) > 0){
								foreach($ProductStd->files as $FileVO){
									$url 	= $FileVO->getUrl();
									$array 	= explode("/", $url);
									$file 	= $array[count($array)-1];
									$Zip->addFile($url, $product_slug_folder."/arquivos/".$file);
								}
							}
							if(count($ProductStd->images) > 0){
								foreach($ProductStd->images as $url){
									//$url 	= $ImageVO->getUrl();
									$array 	= explode("/", $url);
									$file 	= $array[count($array)-1];
									$file	= str_replace("original_", "", $file);
									$Zip->addFile($url, $product_slug_folder."/imagens/".$file);
								}
							}
							if($ProductStd->pdf){
								$url 	= $ProductStd->pdf;
								$array 	= explode("/", $url);
								$file 	= $array[count($array)-1];
								$Zip->addFile($url, $product_slug_folder."/ficha_tecnica.pdf");
							}
						}
						$Zip->close();
					}
					header("Location: ".Config::getRootPath($zip_name));
					exit();
			
		}//end if se mandou ou nao id
		Navigation::redirect("404/");
		exit();
	}
	/**
	 * para listagem de produtos
	 */
	public function init(){
		//lista todos produtos vinculados a categoria 2
		$returnResult 			= new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML 	= new HttpRoot();
		$arrayContentsVO = array();
		//se foi passado o id da categoria entao vai buscar todos os contentents vinculados a mesma
		//echo Debug::li($this->category_id);exit();
			$LinkDAO = LinkDAO::getInstance();
			$category_id = 2;//categoria a que todos os produtos estão vinculados
			$returnDataVO = $LinkDAO->select(LinkDAO::RETURN_VO, "category", $category_id, "content", NULL, 1,NULL, NULL, $order_by = "order", $order_type = NULL);
			//verifica se o resultado é uma categoryVO
			if($returnDataVO->success && count($returnDataVO->result)>0){
				foreach($returnDataVO->result as $LinkVO){
					//Debug::print_r($LinkVO); 
					$tempReturnDataVO = $LinkVO->getLinkedVO();
					//Debug::print_r($tempReturnDataVO);exit();
					if($tempReturnDataVO->success){
						$stdClass = $tempReturnDataVO->result;
						if($stdClass->active == 1){
							$stdClass->files = $stdClass->getFiles();
							
							$arrayContentsVO[] = $stdClass;
							
						}
					}
				}
				//exit();
			}
			//Debug::print_r($arrayContentsVO);
			//exit();
		$retornoDaPaginaHTML->arrayContentsVO = $arrayContentsVO;
		$retornoDaPaginaHTML->checked = DataHandler::getValueByArrayIndex($this->arrayVariable, "checked");
		if($retornoDaPaginaHTML->checked){
			$retornoDaPaginaHTML->checked = explode("|", $retornoDaPaginaHTML->checked);
		}
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);exit();
		return $returnResult;
	}
}