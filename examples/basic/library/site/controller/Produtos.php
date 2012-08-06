<?php

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");
include_once("library/facil3/core/modules/file/dao/FileDAO.class.php");
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Produtos{
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
	 * para listagem de produtos
	 */
	public function init(){
		//lista todos produtos vinculados a categoria 2
		$returnResult = new HttpResult();
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpRoot();
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
						$dads = $stdClass->getCategoriesDad();
						
						if($stdClass->active == 1 && count($dads) > 1){
							//pega o nome da sub categoria e categoria que ele pertence
							//Debug::print_r($dads);
							$arrayContentsVO[] = $stdClass;
							
						}
					}
				}
				//exit();
			}
			DataHandler::objectSort($arrayContentsVO, "title");
		$retornoDaPaginaHTML->arrayContentsVO = $arrayContentsVO;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		//Debug::print_r($returnResult);
		//exit();
		return $returnResult;
	}
	/**
	 * baixar o zip do mal
	 * ?products={product_id:N,gallery:true,dimensions:true,files:[]}|{product_id:N,gallery:true,dimensions:true,files:[]}
	 */
	public function getZip(){
		if(!UserClient::getId() > 0){
			//nao tem permissao
			Navigation::redirect("405");
			exit();
		}
		set_time_limit(0);
		//tratando variaveis enviadas
		$files_id 	= DataHandler::getValueByArrayIndex($_POST, "file_id");
		$imagens 	= DataHandler::getValueByArrayIndex($_POST, "imagens");
		$pdf 		= DataHandler::getValueByArrayIndex($_POST, "pdf");
		//precisa saber quais sao os produtos envolvidos
		$array_products_id 	= array();
		$array_files 		= array();
		if($files_id){
			foreach($files_id as $file_id){
				$temp_array = explode("_", $file_id);
				$product_id = $temp_array[0];
				if(!in_array($product_id, $array_products_id)){
					$array_products_id[] 	= $product_id;
					$array_files[] 			= $product_id;
				}
			}
		}
		if($imagens){
			foreach($imagens as $product_id){
				if(!in_array($product_id, $array_products_id)){
					$array_products_id[] = $product_id;
				}
			}
		}
		if($pdf){
			foreach($pdf as $product_id){
				if(!in_array($product_id, $array_products_id)){
					$array_products_id[] = $product_id;
				}
			}
		}
		
		$ReturnResultVO = new ReturnResultVO();
		
		$ProductVO = new ContentSiteVO();
		$FileVO = new FileVO();
		//se tiver produtos para tratar
		if(count($array_products_id) > 0){
			//a pasta zip precisa existir
			$zip_path = "upload/zip/";
			DataHandler::createFolderIfNotExist($zip_path);
			$array_products_to_zip = array();
			$unique_str = "";
			//nome final do zip que vai baixar, adiciona alguns parametros indicadores
			$zip_name = "teto";
			if(count($array_products_id) > 0){
				//sao varios produtos
				$zip_name = $zip_name."_produtos";
			}
			//cada indice dessa array, tem que ser array, terá um json com as info:
			foreach($array_products_id as $product_id){
				$resultProduto = $ProductVO->setId($product_id, TRUE);
				if($resultProduto->success){
					
					$stdProduct = $ProductVO->toStdClass();
					$array_products_to_zip[] = $stdProduct;
					$array_images = array();
					if($imagens){
						if(in_array($product_id, $imagens)){
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
						}
					}
					$stdProduct->images = $array_images;
					
					$array_file_vo = array();
					//cria a pasta do produto no zip
					$product_folder_name = DataHandler::strToURL($stdProduct->title);
					if(count($array_files) > 0){
						if(in_array($product_id, $array_files)){
							//esse produto pediu algum file
							
							$temp_array_files		= $ProductVO->getFiles();
							foreach($temp_array_files as $FileVO){
								if(in_array($FileVO->id, $files_id)){									
									$array_file_vo[] = $FileVO;
									$unique_str .= "|f.".$file_id;
									//add a url do arquivo no zip na pasta
								}
							}
						}
					}
					$stdProduct->files 	= $array_file_vo;
					$stdProduct->pdf 	= null;
					//verificar se quer o pdf
					if(count($pdf) > 0){
						$unique_str .= "|pdf!|";
						//quero pdf
						//ver com onde foi salvo
						if(in_array($product_id ,$pdf)){
							//ele quer esse pdf, provavelmente se chegou aqui é porque ele realmente exite
							//mas vou conferir de novo
							$pdf_url = "upload/pdf/$product_id/".$ProductVO->slug.".pdf"; 
							if(file_exists($pdf_url)){
								$stdProduct->pdf = $pdf_url;
							}
						}
					}
				}//end if produto sucess
			}
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
					foreach($ProductStd->files as $FileVO){
						$url 	= $FileVO->getUrl();
						$array 	= explode("/", $url);
						$file 	= $array[count($array)-1];
						$Zip->addFile($url, $product_slug_folder."/arquivos/".$file);
					}
					foreach($ProductStd->images as $url){
						//$url 	= $ImageVO->getUrl();
						$array 	= explode("/", $url);
						$file 	= $array[count($array)-1];
						$file	= str_replace("original_", "", $file);
						$Zip->addFile($url, $product_slug_folder."/imagens/".$file);
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
		}
		exit();
	}
}