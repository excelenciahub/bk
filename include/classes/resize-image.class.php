<?php
    class ResizeImage{
        // *** Class variables
        private $image;
        private $width;
        private $height;
        private $imageResized;

        /**
         * @param string filename
         * @retrun void
         * */
        function __construct($fileName){
            // *** Open up the file
            $this->image = $this->openImage($fileName);
            // *** Get width and height
            $this->width  = imagesx($this->image);
            $this->height = imagesy($this->image);
        }
        
        /**
         * @param string file
         * @return image object
         * */
        private function openImage($file){
            $image_type = getimagesize($file)[2];
            switch($image_type){
                case IMAGETYPE_JPEG:
                    $img = @imagecreatefromjpeg($file);
                    break;
                case IMAGETYPE_GIF:
                    $img = @imagecreatefromgif($file);
                    break;
                case IMAGETYPE_PNG:
                    $img = @imagecreatefrompng($file);
                    break;
                default:
                    $img = false;
                    break;
            }
            return $img;
        }
        
        /**
         * @param int width
         * @param int height
         * @param string option (options: exact, portrait, landscape, auto, crop)
         * @return void
         * */
        public function resizeImage($newWidth, $newHeight, $option="auto"){         
            // *** Get optimal width and height - based on $option
            $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
            $optimalWidth  = $optionArray['optimalWidth'];
            $optimalHeight = $optionArray['optimalHeight'];
            // *** Resample - create image canvas of x, y size
            $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
            imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
            // *** if option is 'crop', then crop too
            if ($option == 'crop'){
                $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
            }
        }
        
        /**
         * @param int width
         * @param int height
         * @param string option (options: exact, portrait, landscape, auto, crop)
         * @return array
         * */
        private function getDimensions($newWidth, $newHeight, $option){
           switch($option){
                case 'exact':
                    $optimalWidth = $newWidth;
                    $optimalHeight= $newHeight;
                    break;
                case 'portrait':
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight= $newHeight;
                    break;
                case 'landscape':
                    $optimalWidth = $newWidth;
                    $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                    break;
                case 'auto':
                    $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                    $optimalWidth = $optionArray['optimalWidth'];
                    $optimalHeight = $optionArray['optimalHeight'];
                    break;
                case 'crop':
                    $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                    $optimalWidth = $optionArray['optimalWidth'];
                    $optimalHeight = $optionArray['optimalHeight'];
                    break;
            }
            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }
        
        /**
         * @param int height
         * @return int width
         * */
        private function getSizeByFixedHeight($newHeight){
            $ratio = $this->width / $this->height;
            $newWidth = $newHeight * $ratio;
            return $newWidth;
        }
        
        /**
         * @param int width
         * @return int height
         * */
        private function getSizeByFixedWidth($newWidth){
            $ratio = $this->height / $this->width;
            $newHeight = $newWidth * $ratio;
            return $newHeight;
        }
        
        /**
         * @param int width
         * @param int height
         * @return array
         * */
        private function getSizeByAuto($newWidth, $newHeight){
            if ($this->height < $this->width){                // *** Image to be resized is wider (landscape)
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            }
            elseif ($this->height > $this->width){
                // *** Image to be resized is taller (portrait)
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            }
            else{
                // *** Image to be resizerd is a square
                if ($newHeight < $newWidth){
                    $optimalWidth = $newWidth;
                    $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                }
                else if ($newHeight > $newWidth){
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight= $newHeight;
                }
                else{
                    // *** Sqaure being resized to a square
                    $optimalWidth = $newWidth;
                    $optimalHeight= $newHeight;
                }
            }
            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }
        
        /**
         * @param int width
         * @param int height
         * @return array
         * */
        private function getOptimalCrop($newWidth, $newHeight){
            $heightRatio = $this->height / $newHeight;
            $widthRatio  = $this->width /  $newWidth;
            
            if($heightRatio < $widthRatio){
                $optimalRatio = $heightRatio;
            }
            else{
                $optimalRatio = $widthRatio;
            }
         
            $optimalHeight = $this->height / $optimalRatio;
            $optimalWidth  = $this->width  / $optimalRatio;
         
            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }
        
        /**
         * @param int width
         * @param int height
         * @param int new width
         * @param int new height
         * @return void
         * */
        private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
            // *** Find center - this will be used for the crop
            $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
            $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
            $crop = $this->imageResized;
            //imagedestroy($this->imageResized);
            // *** Now crop from center to exact requested size
            $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
            imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
        }
        
        /**
         * @param string path
         * @param string image path
         * @param int quality
         * @return void
         * */
        public function saveImage($savePath, $image, $imageQuality="100"){
            // *** Get extension
            $image_type = getimagesize($image)[2];
            switch($image_type){
                case IMAGETYPE_JPEG:
                    if(imagetypes() & IMG_JPG){
                        imagejpeg($this->imageResized, $savePath, $imageQuality);
                    }
                    break;
                case IMAGETYPE_GIF:
                    if(imagetypes() & IMG_GIF){
                        imagegif($this->imageResized, $savePath);
                    }
                    break;
                case IMAGETYPE_PNG:
                
                
        
                
                    // *** Scale quality from 0-100 to 0-9
                    $scaleQuality = round(($imageQuality/100) * 9);
                    // *** Invert quality setting as 0 is best, not 9
                    $invertScaleQuality = 9 - $scaleQuality;
                    if(imagetypes() & IMG_PNG){
                        imagepng($this->imageResized, $savePath, $invertScaleQuality);
                    }
                    break;
                default:
                    break;
            }
            imagedestroy($this->imageResized);
        }

    }
?>