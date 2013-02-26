<?php

   # ========================================================================#
   #
   #  Author:    Rajani .B
   #  Version:     1.0
   #  Date:      07-July-2010
   #  Purpose:   Resizes and saves image
   #  Requires : Requires PHP5, GD library.
   #  Usage Example:
   #                     include("classes/resize_class.php");
   #                     $resizeObj = new resize('images/cars/large/input.jpg');
   #                     $resizeObj -> resizeImage(150, 100, 0);
   #                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
   #
   #
   # ========================================================================#


        Class ResizeImage
        {
        	//tipos possiveis
        	/**
        	 * @var string da o strash
        	 */
        	const MODE_TYPE_EXACT 			= 'exact';
        	/**
        	 * @var string RETRATO
        	 */
        	const MODE_TYPE_PORTRAIT		= 'portrait';
			/**
			 * @var string paisagem
			 */
			const MODE_TYPE_LANDSCAPE		= 'landscape';
            /**
             * @var string calculo automático respeitando o máximo possível
             */
            const MODE_TYPE_AUTO			= 'auto';
            /**
             * @var string cropa
             */
            const MODE_TYPE_CROP			= 'crop';
            
            // *** Class variables
            private $image;
            private $width;
            private $height;
            private $imageResized;
            private $extension_file;
            function __construct($fileName){
                // *** Open up the file
                $this->image = $this->openImage($fileName);
                // save the extension
                $this->extension_file = strtolower(DataHandler::returnExtensionOfFile($fileName));
                // *** Get width and height
                $this->width  = imagesx($this->image);
                $this->height = imagesy($this->image);
            }

            ## --------------------------------------------------------
			private function openImage($file){
                // *** Get extension
                $extension = strtolower(strrchr($file, '.'));
                //$extension = DataHandler::returnExtensionOfFile($file);
                switch($extension)
                {
                    case '.jpg':
                    case '.jpeg':
                        $img = @imagecreatefromjpeg($file);
                        break;
                    case '.gif':
                        $img = @imagecreatefromgif($file);
                        break;
                    case '.png':
                        $img = @ImageCreateFromPNG($file);
//                        //abaixo do php.net
//                        imagealphablending($img, false);
//						imagesavealpha($img, true);
                        
                        break;
                    default:
                        $img = false;
                        break;
                }
                return $img;
            }

            ## --------------------------------------------------------

            /**
             * Enter description here ...
             * @param int $newWidth
             * @param int $newHeight
             * @param string $option
             */
            public function resizeImage($newWidth, $newHeight, $option = self::MODE_TYPE_AUTO){
                // *** Get optimal width and height - based on $option
                $optionArray = $this->getDimensions($newWidth, $newHeight, $option);
//				echo Debug::li("resizeImage : $newWidth, $newHeight, $option ");
                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
//                echo Debug::li(" depois resizeImage : $optimalWidth, $optimalHeight, $option ");
//                exit();
                // *** Resample - create image canvas of x, y size
                $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
                if($this->extension_file == "png") imagealphablending($this->imageResized, false);
                
                imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
            	if($this->extension_file == "png"){
                	imagesavealpha($this->imageResized, true);
                }
                
                // *** if option is 'crop', then crop too
                if ($option == 'crop') {
                    $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
                }
                
            	
            }

            ## --------------------------------------------------------
            
            /**
             * @param $newWidth
             * @param $newHeight
             * @param $option
             * @return array tosca com 2 indices
             */
            private function getDimensions($newWidth, $newHeight, $option)
            {
            	try{
            		
            	}catch(Exception $e){
            		
            	}
               switch ($option)
                {
                    case self::MODE_TYPE_EXACT:
                        $optimalWidth = $newWidth;
                        $optimalHeight= $newHeight;
                        break;
                    case self::MODE_TYPE_PORTRAIT:
                        $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                        $optimalHeight= $newHeight;
                        break;
                    case self::MODE_TYPE_LANDSCAPE:
                        $optimalWidth = $newWidth;
                        $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                        break;
                    case self::MODE_TYPE_AUTO:
                    		if(($this->width/$newWidth)>($this->height/$newHeight)){
								return $this->getDimensions($newWidth, $newHeight, self::MODE_TYPE_LANDSCAPE);
							} elseif(($this->width/$newWidth)<($this->height/$newHeight)){
								//calcula pela largura
								return $this->getDimensions($newWidth, $newHeight, self::MODE_TYPE_PORTRAIT);
								
							} else {
								//a relação é igual, pode manter o valor
								$optimalWidth	= $newWidth;
								$optimalHeight	= $newHeight;
							}
                        break;
                    case self::MODE_TYPE_CROP:
                    default:
                        $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                        $optimalWidth = $optionArray['optimalWidth'];
                        $optimalHeight = $optionArray['optimalHeight'];
                        break;
                }
                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            private function getSizeByFixedHeight($newHeight)
            {
                $ratio = $this->width / $this->height;
                $newWidth = $newHeight * $ratio;
                return $newWidth;
            }

            private function getSizeByFixedWidth($newWidth)
            {
                $ratio = $this->height / $this->width;
                $newHeight = $newWidth * $ratio;
                return $newHeight;
            }

            private function getSizeByAuto($newWidth, $newHeight)
            {
            	
	            if(($this->width/$newWidth)>($this->height/$newHeight)){
					$optimalWidth	 = $newWidth;
					$optimalHeight	 = NULL;
				} elseif(($this->width/$newWidth)<($this->height/$newHeight)){
					$optimalHeight 	 = $newHeight;
					$optimalWidth 	 = NULL;
				} else {
					$optimalWidth 	 = $newWidth;
					$optimalHeight 	 = $newHeight;
				}

                    if ($optimalHeight < $optimalWidth) {
                        $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                    } else if ($newHeight > $newWidth) {
                        $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    }

                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            private function getOptimalCrop($newWidth, $newHeight)
            {

                $heightRatio = $this->height / $newHeight;
                $widthRatio  = $this->width /  $newWidth;

                if ($heightRatio < $widthRatio) {
                    $optimalRatio = $heightRatio;
                } else {
                    $optimalRatio = $widthRatio;
                }

                $optimalHeight = $this->height / $optimalRatio;
                $optimalWidth  = $this->width  / $optimalRatio;

                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
            {
            	// *** Find center - this will be used for the crop
            	$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
                $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
            	
                // *** Resample - create image canvas of x, y size
                $crop = imagecreatetruecolor($newWidth , $newHeight);
                if($this->extension_file == "png") imagealphablending($crop, false);
                
                imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
                	//
                		imagecopyresampled($crop, $this->imageResized, 0, 0, $cropStartX, $cropStartY, 	$newWidth, 		$newHeight , 	$newWidth, 		$newHeight);
            	if($this->extension_file == "png"){
                	imagesavealpha($crop, true);
                }
            	
            	
            	$this->imageResized = $crop;
            	
            	            	
//                // *** Find center - this will be used for the crop
//                $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
//                $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
//
//                $crop = $this->imageResized;
//                //imagedestroy($this->imageResized);
//
//                // *** Now crop from center to exact requested size
//                $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
//                if($this->extension_file == "png") imagealphablending($crop, false);
//                imagecopyresampled($this->imageResized, $crop	, 0, 0, $cropStartX, $cropStartY, 	$newWidth, 		$newHeight , 	$newWidth, 		$newHeight);
                
            }

            ## --------------------------------------------------------

            public function saveImage($savePath, $imageQuality="100")
            {
                // *** Get extension
                $extension = strrchr($savePath, '.');
                   $extension = strtolower($extension);

                switch($extension)
                {
                    case '.jpg':
                    case '.jpeg':
                        if (imagetypes() & IMG_JPG) {
                            imagejpeg($this->imageResized, $savePath, $imageQuality);
                        }
                        break;

                    case '.gif':
                        if (imagetypes() & IMG_GIF) {
                            imagegif($this->imageResized, $savePath);
                        }
                        break;

                    case '.png':
                        // *** Scale quality from 0-100 to 0-9
                        $scaleQuality = round(($imageQuality/100) * 9);

                        // *** Invert quality setting as 0 is best, not 9
                        $invertScaleQuality = 9 - $scaleQuality;

                        if (imagetypes() & IMG_PNG) {
                        	//echo Debug::li("vai salvar a imagem: $savePath, $invertScaleQuality ");
                        	imagepng($this->imageResized, $savePath, $invertScaleQuality);
                        	//exit();
                             
                        }
                        break;

                    // ... etc

                    default:
                        // *** No extension - No save.
                        break;
                }

                imagedestroy($this->imageResized);
            }


            ## --------------------------------------------------------

        } 