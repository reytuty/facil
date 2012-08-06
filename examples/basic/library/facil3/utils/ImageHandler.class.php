<?php
/**
 * @copyright iDress - All rights reserved | Todos os direitos reservados
 * 
 * Class responsable in create and show Thumb Images, using how original image
 * that had been passed by argument in the Constructor method.
 * 
 * Classe respons�vel em criar e exibir miniatura de imagens, utilizando como imagem original
 * a mesma que foi passado como argumento para o m�todo construtor.
 * 
 * @date 2007/09/24 11:16
 * @author Renato Seiji Miawaki / Victor Godinho
 * @company iDress Assessoria e Solu��es em Inform�tica
 * @version 1.3.2
 * @upgrade Renato Seiji Miawaki
 * @upgrade Renato Seiji Miawaki
 */


class ImageHandler {
	var $width;
	var $height;
	var $setSiteURL;
	var $image;
	function getDimensoes(){
		if($this->width == null || $this->height == null){
			$originalImage = $this->createImage();
			$this->width  = imagesx($originalImage);
			$this->height = imagesy($originalImage);
		}
	}
	function getWidth(){
		$this->getDimensoes();
		return $this->width;
	}
	function getHeight(){
		$this->getDimensoes();
		return $this->height;
	}
	/**
	 * Constructor method, responsable in store the 
	 * original image that will be worked in.
	 *
	 * @param URL - URL of the original Image.
	 */
	function __construct($url) {
		$this->image = $url;
	}
	
	/**
	 * Creation of the original image object.
	 *
	 * @return object of the image created.
	 */
	 function createImage(){
	 	//est� conferindo o arquivo pela exten��o contando que foi conferido o tipo de arquivo ao recebe-lo
		$extensao = DataHandler::returnExtensionOfFile($this->image);
//		echo Debug::li(filetype($this->image));
//		exit();
		switch(strtolower($extensao)){
			case 'jpg':
			case 'jpeg':
				return imagecreatefromjpeg($this->image);
				break;
			case 'gif':
				return imagecreatefromgif($this->image);
				break;
			case 'png':
				return imagecreatefrompng($this->image);
				break;
			case 'bmp':
				return imagecreatefromwbmp($this->image);
				break;
			default:
				return imagecreatefromjpeg($this->image);
				break;
		}
	}
	
	/**
	 * Create an thumb image of the original, only putting in memory,
	 * with the rights width and height passed.
	 * By default if the Height didnt be passed (NULL), this Height is
	 * setted proporcionaly with the image.
	 *
	 * @param newWidth  - the new width of the thumb.
	 * @param newHeight - the new height of the thumb.
	 * @return the thumb created.
	 */
	 function createImageResized($newWidth=NULL, $newHeight = NULL, $limitWidth=NULL, $limitHeight=NULL, $cortar = false) {
		$originalImage = $this->createImage();
		
		$fullWidth  = imagesx($originalImage);
		$fullHeight = imagesy($originalImage);
		if ($newWidth == NULL and $newHeight == NULL){
			if($limitWidth==NULL and $limitHeight==NULL){
				return false;
			}
			if(($fullWidth/$limitWidth)>($fullHeight/$limitHeight)){
				if(!$cortar){
					$newWidth = $limitWidth;
					$newHeight = NULL;
				} else {
					$newHeight = $limitHeight;
					$newWidth = NULL;
				}
			} elseif(($fullWidth/$limitWidth)<($fullHeight/$limitHeight)){
				if(!$cortar){
					$newHeight = $limitHeight;
					$newWidth = NULL;
				} else {
					$newWidth = $limitWidth;
					$newHeight = NULL;
				}
			} else {
				$newWidth = $limitWidth;
				$newHeight = $limitHeight;
			}
		}
		if($newWidth == NULL){
			$newWidth = ($fullWidth*$newHeight)/$fullHeight;
		} elseif ($newHeight == NULL) {
			$newHeight = ($fullHeight*$newWidth)/$fullWidth;
		}
		$thumbImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresized($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $fullWidth, $fullHeight);
		if($cortar){
			$thumbImage2 = imagecreatetruecolor($limitWidth, $limitHeight);
			imagecopy($thumbImage2, $thumbImage, 0, 0, 0, 0, $limitWidth, $limitHeight);
			imagedestroy($originalImage);
			return $thumbImage2;
		}
		
		imagedestroy($originalImage);
		return $thumbImage;
	}
	function createImageNoResized($newWidth, $newHeight = NULL) {
		$originalImage = $this->createImage();
		
		$fullWidth  = imagesx($originalImage);
		$fullHeight = imagesy($originalImage);
		
		$newWidth  = $newHeight;
		$newHeight = ($newWidth == NULL) ? (($fullWidth*$alturad)/$fullHeight) : ($newWidth);
		
		$thumbImage = imagecreatetruecolor($newWidth, $newHeight);
		
		imagecopy($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight);
		
		imagedestroy($originalImage);
		
		return $thumbImage;
	}
	
	/**
	 * Create a thumb image, and save it in the specific URL with the
	 * with and height specificed in the arguments.
	 * By default if the Height didnt be passed (NULL), this Height is
	 * setted proporcionaly with the image.
	 *
	 * @param newUrl    - the new Url (with name) of the image created.
	 * @param newWidth  - new width of the thumb.
	 * @param newHeight - new height of the thumb.
	 */
	function createThumb($newUrl, $newWidth = NULL, $newHeight = NULL) {
		$thumb = $this->createImageResized($newWidth, $newHeight);
		imagejpeg($thumb, $newUrl, 100);
		
		imagedestroy($thumb);
		return true;
	}
	function createThumbNoCut($newUrl, $newWidth = NULL, $newHeight = NULL) {
		//$thumb = $this->createImageResized(NULL, NULL, $newWidth, $newHeight, false);
		$thumb = $this->createImageResized(NULL , NULL, $newWidth, $newHeight, false);
		imagejpeg($thumb, $newUrl, 100);
		
		imagedestroy($thumb);
		return true;
	}
	function createThumbLimit($newUrl, $newWidth, $newHeight){
		$thumb = $this->createImageResized(NULL , NULL, $newWidth, $newHeight, true);
		imagejpeg($thumb, $newUrl, 100);
		
		imagedestroy($thumb);
		if(file_exists($newUrl)){
			return true;
		} else {
			return false;
		}
	}
	function createThumbNoResize($newUrl, $newWidth, $newHeight = NULL) {
		$thumb = $this->createImageNoResized($newWidth, $newHeight);
		
		imagejpeg($thumb, $newUrl, 90);
		
		imagedestroy($thumb);
	}
	/**
	 * Show a thumb of the original image with the
	 * specificed width and height setted by the arguments.
	 * By default if the Height didnt be passed (NULL), this Height is
	 * setted proporcionaly with the image.
	 *
	 * @param newWidth  - new width of the thumb.
	 * @param newHeight - new height of the thumb.
	 * @return the view of the image, to view on browser.
	 */
	function showThumb($newWidth, $newHeight = NULL) {
		header("Content-type: image/jpeg");
		
		$thumb = $this->createImageResized($newWidth, $newHeight, $newWidth, $newHeight, TRUE);
		
		imagejpeg($thumb);
		
		imagedestroy($thumb);
	}
	function showThumbResize($newWidth=NULL, $newHeight = NULL, $direct_show = FALSE, $urlSite = "", $crop = FALSE){
		//mostra uma thumb do tamanho pedido a partir do arquivo original.
//		echo "showThumbResize:".$this->image;
//		exit();
		//descobrindo o nome do arquivo sem exten��o
		$tempArray 			= explode(".", $this->image);
		$extencao 			= $tempArray[count($tempArray)-1];
		$nomeSemExtencao 	= str_replace(".".$extencao, "", $this->image);
//		Debug::li($extencao);
//		exit();
		//criando o nome do arquivo redimencionado
		$novoArquivo 		= "";
		if($crop){
			$novoArquivo 		= $nomeSemExtencao."__".$newWidth."x".$newHeight."_crop.".$extencao;
		} else {
			$novoArquivo 		= $nomeSemExtencao."__".$newWidth."x".$newHeight.".".$extencao;
		}
		//descobrindo a ultima pasta para dar permissao de escrita
		$tempArray 			= explode("/", $nomeSemExtencao);
		$arquivo 			= $tempArray[count($tempArray)-1];
		$ultimaPasta 		= str_replace($arquivo, "", $nomeSemExtencao);
		//tirando o nome do próprio site do nome da pasta
		$ultimaPasta		= str_replace($urlSite,"", $ultimaPasta);
		//dando permissao 777
//		echo Debug::li("ultimaPasta : $ultimaPasta");
		if(file_exists($ultimaPasta)){
			//para melhoria de desempenho, retire esse if
//			echo Debug::li("a pasta : $ultimaPasta EXISTE");
			@chmod($ultimaPasta, 0777);
		}
		//inicia para jpg
		$type_image_header = "image/jpeg";
		switch($extencao){
			case "gif":
				$type_image_header = "image/gif";
			case "jpg":
			default:
				$type_image_header = "image/jpeg";
//			case "png":
//				$type_image_header = "image/png";
		}
		$extencao = strtolower($extencao);
		$urlRelativaArquivo = str_replace($urlSite,"", $novoArquivo);
		if(!file_exists($novoArquivo)){
//			echo "O arquivo ".$novoArquivo." NAO EXISTE a thumb<br>";
//			exit();
//			header("Content-type: $type_image_header");
//			Debug::li(" aa 1 ");
			$thumb = $this->createImageResized(NULL, NULL, $newWidth, $newHeight, $crop);
			//salvando o arqivo no local
//			echo Debug::li($urlRelativaArquivo);
//			DataHandler::writeFile("", "teste.txt", "teste ");
//			DataHandler::writeFile($urlRelativaArquivo, "", "teste ");
//			exit();
			//chmod no arquivo
			@chmod($urlRelativaArquivo, 0777);
			switch($extencao){
				case "gif":
					//imagegif($thumb, $urlRelativaArquivo);
					//break;
				case "png":
//					imagepng($thumb, $urlRelativaArquivo, 100);
//					break;
				case "jpg":
				default:
					imagejpeg($thumb, $urlRelativaArquivo, 100);
					break;
			}
			//mostrando o arquivo pro browser
			if($direct_show){
				//echo "sdfsfs";
				header("Content-type: image/jpeg");
				
				$thumb = $this->createImageResized(NULL, NULL, $newWidth, $newHeight, TRUE);
				
				imagejpeg($thumb);
				
				imagedestroy($thumb);
				exit();
			}
			imagedestroy($thumb);
//			echo Debug::li("Location: ".$this->setSiteURL."$novoArquivo");
//			exit();
			header("Location: ".$this->setSiteURL."$novoArquivo");
			exit();
		} else {
			if($direct_show){
//				echo "sdfsaafs";
//				exit();
				//nem funciona
			}
//			echo Debug::li("Location: ".$this->setSiteURL."$urlRelativaArquivo");
//			exit();
			header("Location: ".$this->setSiteURL."$urlRelativaArquivo");
			exit();
		}
	}
}

//** TESTE Class 

//$image = new Image("a.jpg");
//$image->showThumb(500, NULL);

//$image2 = new Image("c.jpg");
//$image2->showThumbResize(174, 120);
//END[TESTE Class] */

?>