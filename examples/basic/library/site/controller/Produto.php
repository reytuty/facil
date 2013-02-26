<?php

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once("library/facil3/utils/mail/LocawebSMTP.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));
/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class Produto{
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
		//Debug::print_r($this->arrayVariable);
		
		//filtro vindo por parametro é o addres que ele tem que enviar de volta como busca
		//rel ids de produtos relacionados
		
		//exit();
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
				$resultLinks = $LinkDAO->select(DbInterface::RETURN_STD_OBJECT, "content", $table_id=null, $linked_table = 'content', $linked_table_id = $id, $active = 1, $quant_started = NULL, $quant_limit = NULL, $order_by = "order", $order_type = " ASC ");
				if($resultLinks->success && $resultLinks->count_total > 0){
					
					foreach($resultLinks->result as $familia){
						$link = $familia;//$resultLinks->result[0];
						$ContentFamiliaVO = new ContentSiteVO();
						$ContentFamiliaVO->setId($link->table_id, TRUE);
						Debug::print_r($ContentFamiliaVO);
						exit();
						if($ContentFamiliaVO->active > 0){
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
										}
										//Debug::print_r($stdProduto);exit();
									}
								}//end if
							}//end foerach
						}
					}
				}
				//verifica a qual familia esse produto pertence
				$stdProduct->array_produtos_vinculados = $array_links;
				//Debug::print_r($array_links);
				//exit();
				//pegando array de vinculados enviados como id
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
			Navigation::redirect("produtos");
		}
	}

	public function sendToFriend() {
		
			
		
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpRoot();
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);

		if( !isset( $_POST["action"] ) ) {
			$protuct_id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
			$retornoDaPaginaHTML->form_action = Config::getRootPath("produto/send_to_friend") ;
			$retornoDaPaginaHTML->view = "form" ;
			$retornoDaPaginaHTML->product_id = $protuct_id ;
		} else {
				
			$postData = (object) $_POST ;	
				
			
				
			$ContentSiteVO = new ContentSiteVO();
			$ReturnResult_vo = $ContentSiteVO->setId( $postData->id , TRUE);
			
			
			if($ReturnResult_vo->success){
				
				$stdProduct = $ContentSiteVO->toStdClass();

				$stdProduct->array_gallery 	= $ContentSiteVO->getImages(NULL, "gallery", true);
				
				
				// Debug::print_r($stdProduct);  die;
				
				$template = file_get_contents( Config::getFolderView(  "/templates/email_produto.php" ) ) ;

				$tpl_img_path = Config::getRootPath( Config::getFolderView( ) ); 
				$recover_logo = ( $stdProduct->hat == 1 )? '<img style="" src="' . $tpl_img_path . '/assets/images/recover-min.png" />' : "" ;
				
				$first_image = sprintf(  "<img width='400px' src='%s' />" , Config::getRootPath( $stdProduct->array_gallery[0]->url ) );
				
				$replace_from = array(
					"###PRODUCT_URI###" , 
					"###IMG_PATH###" ,
					"###TITLE###" ,
					"###HAT###" ,
					"###CONTENT###" ,
					"###IMG###" ,
					"###SENDER_NAME###" ,
					"###SENDER_EMAIL###" ,
					"###RECEIVER_NAME###" ,
					"###RECEIVER_MAIL###" ,
					"###RECEIVER_MESSAGE###" , 
				) ;
				$replace_to = array(
					Config::getRootPath('produto/id.'.$stdProduct->id.'/'.$stdProduct->slug) ,
					$tpl_img_path ,
					utf8_decode($stdProduct->title) ,
					$recover_logo ,
					$stdProduct->content ,
					$first_image ,
					$postData->sender_name ,
					$postData->sender_email ,
					$postData->receiver_name ,
					$postData->receiver_email ,
					$postData->receiver_message ,
				) ; 
					
				$template = str_replace( $replace_from, $replace_to, $template ) ;
				
				// var_dump( $stdProduct , $postData ) ;
				// echo $template ; die;
				
				
								
				$host = Config::SYSTEM_MAIL_SMTP ;
				$mail = Config::SYSTEM_MAIL_LOGIN ;
				$senha = Config::SYSTEM_MAIL_PASSWORD ;
				
				// var_dump( $host , $mail , $senha ) ; die ;
				ob_start();
				$smtp = new Smtp($host, 587) ;
				$smtp->user = $mail ;
				$smtp->pass = $senha ;
				$smtp->debug = true ;
				
				// $from = "'" . $postData->sender_name . "' <" . Config::SYSTEM_MAIL_FROM . ">" ;
				// $to = "'" . $postData->sender_name . "' <" . $postData->receiver_mail . ">" ;
				
				$from = Config::SYSTEM_MAIL_FROM ;
				$to = $postData->receiver_email ;

				
				$subject = "Indicação de produto";
				$msg = $template ;
				
				$retornoDaPaginaHTML->sucess = $smtp->Send($to, $from, $subject, $msg, "text/html") ? true : false ;
				ob_end_clean();
				//var_dump( $send ) ;
			
			}	
			
			$retornoDaPaginaHTML->view = "result" ;
			
		}
		
		return $returnResult ;
		
		
	}
	
	

}