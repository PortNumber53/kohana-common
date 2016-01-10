<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_Dynimage
 */
class Controller_Common_Core_Dynimage extends Controller_Website
{
    public $auto_render = false;

    public function action_view()
    {
        $request = $this->request->param('request');
        $type = $this->request->param('type');
        $full_request = ($request === '/') ? $request : "$request.$type";

        $file_name = DATAPATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $full_request;

        $mime_type = mime_content_type($file_name);
        $this->response->headers('content-type', $mime_type);
        echo file_get_contents($file_name);
    }

    public function action_resize()
    {
        $pictureid = $this->request->param('pictureid');
        $request = $this->request->param('request');
        $type = $this->request->param('type');
        $newWidth = $this->request->param('width');
        $newHeight = $this->request->param('height');
        $option = $this->request->param('method');

        $full_request = ($request === '/') ? $request : "$request.$type";

        $model_picture = new Model_Picture();
        $picture_data = $model_picture->getDataById($pictureid);

        $filename = DATAPATH . 'upload' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $picture_data['folder'] . DIRECTORY_SEPARATOR . $picture_data['image_filepath'];
        $mime_type = mime_content_type($filename);
        $this->response->headers('content-type', $mime_type);

        // *** Open up the file
        $this->image = $this->openImage($filename);
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);

        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);

        $optimalWidth = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];

        // *** Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width,
            $this->height);


        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }

        // *** Get extension
        $imageQuality = 100;
        $extension = strrchr($filename, '.');
        $extension = strtolower($extension);

        $name = explode(".", basename($filename));
        header("Content-Disposition: inline; filename=" . $name[0] . "_t.$extension");
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT');
        header("Cache-Control: public");
        header("Pragma: public");


        switch (strtolower($extension)) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    header("Content-Type: image/jpeg");
                    imagejpeg($this->imageResized, null, $imageQuality);
                }
                break;

            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    header("Content-Type: image/gif");
                    imagegif($this->imageResized);
                }
                break;

            case '.png':
                // *** Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality / 100) * 9);

                // *** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;

                if (imagetypes() & IMG_PNG) {
                    header("Content-Type: image/png");
                    imagepng($this->imageResized, null, $invertScaleQuality);
                }
                break;

            // ... etc

            default:
                // *** No extension - No save.
                break;
        }

        imagedestroy($this->imageResized);

    }


    private function openImage($file)
    {
        // *** Get extension
        $extension = strtolower(strrchr($file, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }

        return $img;
    }

    private function getDimensions($newWidth, $newHeight, $option)
    {
        switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight = $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
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

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight,);
    }

    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;

        return $newWidth;
    }

    ## --------------------------------------------------------

    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;

        return $newHeight;
    }

    ## --------------------------------------------------------

    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width) // *** Image to be resized is wider (landscape)
        {
            $optimalWidth = $newWidth;
            $optimalHeight = $this->getSizeByFixedWidth($newWidth);
        } elseif ($this->height > $this->width) // *** Image to be resized is taller (portrait)
        {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight = $newHeight;
        } else // *** Image to be resizerd is a square
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
            } else {
                if ($newHeight > $newWidth) {
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight = $newHeight;
                } else {
                    // *** Sqaure being resized to a square
                    $optimalWidth = $newWidth;
                    $optimalHeight = $newHeight;
                }
            }
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight,);
    }

    private function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = $this->height / $newHeight;
        $widthRatio = $this->width / $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth = $this->width / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight,);
    }

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // *** Find center - this will be used for the crop
        $cropStartX = ($optimalWidth / 2) - ($newWidth / 2);
        $cropStartY = ($optimalHeight / 2) - ($newHeight / 2);

        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);

        // *** Now crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth,
            $newHeight);
    }
}
