<?php


//verificar a necessidade dissso no servidor WEB
error_reporting(0);
ini_set("display_errors", "0"); 
ini_set('memory_limit', '512M') ;

include_once("library/facil3/core/modules/category/dao/CategoryDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/content/dao/ContentSiteDAO.class.php";
include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once Config::getFolderView("/content_module/HttpContentModule.class.php");

include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpRoot.class.php"));

include_once("library/dompdf/dompdf_config.inc.php");

/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class PdfDownload{
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	
	public function __construct(){
		
	}
	/**
	 * para detalhe de um produto
	 */
	public function init(){
		//nada aqui
	}
	/**
	 * 
	 * Para acessar isso aqui seria a url: pdf_download/produto/id.N/
	 */
	public function produto( $id = false ){
				

		
		if( $id == FALSE)
			$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		
		if( $id > 0 ){
			
			$ContentSiteVO = new ContentSiteVO();
			$ReturnResult_vo = $ContentSiteVO->setId($id, TRUE);
			if($ReturnResult_vo->success){
				
				
				$stdProduct = $ContentSiteVO->toStdClass();
				$stdProduct->array_gallery 		= $ContentSiteVO->getImages(NULL, "gallery", true);
				$stdProduct->array_dimensions	= $ContentSiteVO->getImages(NULL, "dimensions", true);
				$stdProduct->array_video		= $ContentSiteVO->getImages(NULL, "video", true);
				$stdProduct->array_360			= $ContentSiteVO->getImages(NULL, "360", true);
				$stdProduct->array_tagged		= $ContentSiteVO->getImages(NULL, "tagged", true);
				
				
				$html_start = "<html><body>" ;
				$html_end = "</body></html>" ;
				$detail_page = file_get_contents( Config::getFolderView("/PdfDownload/detail.tpl") ) ;
				$image_page = file_get_contents( Config::getFolderView("/PdfDownload/image.tpl") ) ;
				$dimension_page = file_get_contents( Config::getFolderView("/PdfDownload/dimension.tpl") ) ;
				
				$tpl_img_path =  "view/site/";//Config::getAsset()  ; 
				
				$recover_logo = ( $stdProduct->hat == 1 )  ? '<img style=" margin-top: 4px; margin-left: 5px; " src="' . $tpl_img_path . '/PdfDownload/recover_logo.png" />' : "" ;
				
				$stdProduct->description  = utf8_decode( $stdProduct->description  ) ;
				$stdProduct->title = utf8_decode( $stdProduct->title );
				$stdProduct->content =  utf8_decode( $stdProduct->content );
				
				$common_tpl_data = array( "###IMG_TPL_PATH###" , "###content###" , "###title###" , "###PRODUCT_URI###" , "###recover_logo###" ) ;
				$common_data =  array( $tpl_img_path , $stdProduct->content ,  $stdProduct->title , Config::getRootPath('produto/id.'.$stdProduct->id.'/'.$stdProduct->slug) , $recover_logo ) ;
				
				$detail_page = str_replace( $common_tpl_data , $common_data , $detail_page ) ;
				$image_page = str_replace( $common_tpl_data , $common_data , $image_page ) ;
				$dimension_page = str_replace( $common_tpl_data , $common_data , $dimension_page ) ;
						
				
				
				$detail_page = str_replace( "###description###" , $stdProduct->description , $detail_page ) ;
				
				
				$html = $html_start . $detail_page ;
				$w = 600 ; 
				$h = 550 ;
					
				foreach ( $stdProduct->array_gallery as $image ) {
					// $new_page = str_replace( "###IMAGE_SRC###" ,  Config::getRootPath( "image/get_image/image_id." .  $image->id . "/max_width.600/max_height.525/" ) . "/max_width.600/max_height.625/crop.1/"  , $image_page ) ;
					$img_name = preg_replace( "/\.jpg$/" , "" , $image->url ) ;
					$sys_img = $img_name . "_w{$w}_h{$h}_m_cache_crop.jpg"  ; 
					$new_page = str_replace( "###IMAGE_SRC###" , $sys_img , $image_page ) ;
					$html.= $new_page ;
				}
				
				foreach ( $stdProduct->array_dimensions as $dimension ) {
					// $new_page = str_replace( "###IMAGE_SRC###" ,    Config::getRootPath( "image/get_image/image_id." .  $dimension->id . "/max_width.600/max_height.525/" )   , $dimension_page ) ;
					
					$img_name = preg_replace( "/\.jpg$/" , "" , $dimension->url ) ;
					$sys_img = $img_name . "_w{$w}_h{$h}_m_cache_crop.jpg"  ; 
					$new_page = str_replace( "###IMAGE_SRC###" , $sys_img , $dimension_page ) ;
					
					$html.= $new_page ;
				}
				
				
				//$html.= $html_end ;
				
				// var_dump($html); die;
				
				$dompdf = new DOMPDF();
				$dompdf->load_html($html);
				$dompdf->render();
				
				//$dompdf->stream( "upload/pdf/" . $stdProduct->id . "/" . $stdProduct->slug . ".pdf") ;
				$file =  $stdProduct->slug . ".pdf" ;
				$path = "upload/pdf/" . $stdProduct->id . "/" ;
				if( ! dir( $path ) ) {
					mkdir( $path , 0777 , true ) ;
				}
				
				$html_file = "print.html" ;
				$html = str_replace( "src=\"", "src=\"" . Config::getRootPath()  , $html ) ;
				$fp = fopen( $path . $html_file , "w"); 
				fwrite($fp, $html); 
				fclose($fp); 
				
				
				
				$pdfoutput = $dompdf->output(); 
				$filename = $output; 
				$fp = fopen( $path . $file , "w"); 
				fwrite($fp, $pdfoutput); 
				fclose($fp); 
				
				
				return true ;
				
			} else {
				return false ;
			}
		} else {
			//não mandou o id, vai pra listagem ((!?) TODO: ver se é isso mesmo)
			Navigation::redirect("produtos");
		}
	}


	/**
	 * 
	 * Para acessar isso aqui seria a url: pdf_download/produto/id.N/
	 */
	public function cliente( $id = false ){
		if( $id == FALSE)
			$id = DataHandler::forceInt(DataHandler::getValueByArrayIndex($this->arrayVariable, "id"));
		
		if($id > 0){
			$ContentSiteVO = new ContentSiteVO();
			$ReturnResult_vo = $ContentSiteVO->setId($id, TRUE);
			if($ReturnResult_vo->success){
				$stdProduct = $ContentSiteVO->toStdClass();
				$stdProduct->array_gallery 		= $ContentSiteVO->getImages(NULL, "gallery", true);
				$stdProduct->array_tagged		= $ContentSiteVO->getImages(NULL, "tagged", true);
				$html_start = "<html><body>" ;
				$html_end = "</body></html>" ;
				$detail_page = file_get_contents( Config::getFolderView("/PdfDownload/detail.tpl") ) ;
				$image_page = file_get_contents( Config::getFolderView("/PdfDownload/image.tpl") ) ;
				$dimension_page = file_get_contents( Config::getFolderView("/PdfDownload/dimension.tpl") ) ;
				
				$tpl_img_path = "view/site/" ;  //Config::getAsset() ; 
				
				$recover_logo = ( $stdProduct->hat == 1 )  ? '<img style=" margin-top: 4px; margin-left: 5px; " src="' . $tpl_img_path . '/PdfDownload/recover_logo.png" />' : "" ;
				
				$stdProduct->description  = utf8_decode( $stdProduct->description  ) ;
				$stdProduct->title = utf8_decode( $stdProduct->title );
				$stdProduct->content =  utf8_decode( $stdProduct->content );
				
				$common_tpl_data = array( "###IMG_TPL_PATH###" , "###content###" , "###title###" , "###PRODUCT_URI###" , "###recover_logo###" ) ;
				$common_data =  array( $tpl_img_path , $stdProduct->content ,  $stdProduct->title , Config::getRootPath('produto/id.'.$stdProduct->id.'/'.$stdProduct->slug) , $recover_logo ) ;
				
				$detail_page = str_replace( $common_tpl_data , $common_data , $detail_page ) ;
				$image_page = str_replace( $common_tpl_data , $common_data , $image_page ) ;
				$dimension_page = str_replace( $common_tpl_data , $common_data , $dimension_page ) ;
						
				
				$detail_page = str_replace( "###description###" , $stdProduct->description , $detail_page ) ;
				
				
				$html = $html_start . $detail_page ;
				
				foreach ( $stdProduct->array_gallery as $image ) {
					$w = 600 ; 
					$h = 550 ;
					$img_url =  "image/get_image/image_id." .  $image->id . "/max_width.{$w}/max_height.{$h}/" ;
					
					// Debug::print_r( $img_url ) ;
					
					// $tmp = file_get_contents( $img_url ) ;
					// unset( $tmp ) ;					
					// $new_page = str_replace( "###IMAGE_SRC###" , Config::getRootPath( "image/get_image/image_id." .  $image->id . "/max_width.600/max_height.525/" )  , $image_page ) ;
					// $new_page = str_replace( "###IMAGE_SRC###" , $image->url  , $image_page ) ;
					
					
					$img_name = preg_replace( "/\.jpg$/" , "" , $image->url ) ;
					
					// $sys_img = $img_name . "_w{$w}_h{$h}_m_no_crop.jpg"  ;
					$sys_img = $img_name . "_w{$w}_h{$h}_m_cache_crop.jpg"  ;
					 
					
					
					
					// Debug::print_r($sys_img ) ;
					// var_dump( file_exists($sys_img) ) ;
					// die;
					 $new_page = str_replace( "###IMAGE_SRC###" ,$sys_img , $image_page ) ;
					// Debug::print_r( $image ) ; die;
					
					
					
					$html.= $new_page ;
					// break;
				}
				
				$html.= $html_end ;
				
				
				// echo $html ; die; 
				
				
				$dompdf = new DOMPDF();
				$dompdf->load_html($html);
				$dompdf->render();
				
				//$dompdf->stream( "upload/pdf/" . $stdProduct->id . "/" . $stdProduct->slug . ".pdf") ;
				$file =  $stdProduct->slug . ".pdf" ;
				$path = "upload/pdf/" . $stdProduct->id . "/" ;
				if( ! dir( $path ) ) {
					mkdir( $path , 0777 , true ) ;
				}
				
				$html_file = "print.html" ;
				$html = str_replace( "src=\"", "src=\"" . Config::getRootPath()  , $html ) ;
				
				
				$fp = fopen( $path . $html_file , "w"); 
				fwrite($fp, $html); 
				fclose($fp); 
				
				
				$pdfoutput = $dompdf->output(); 
				// $filename = $output; 
				$fp = fopen( $path . $file , "w"); 
				fwrite($fp, $pdfoutput); 
				fclose($fp); 
				
				return true ;
				
			} else {
				//não achou um produto com esse id
				return false;
				Navigation::redirect("");
			}
		} else {
			return false;
			//não mandou o id, vai pra listagem ((!?) TODO: ver se é isso mesmo)
			Navigation::redirect("clientes");
		}
	}
}
