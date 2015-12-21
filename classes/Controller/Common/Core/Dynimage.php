<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_Dynimage
 */
class Controller_Common_Core_Dynimage extends Controller_Website
{
    public $auto_render = false;

    public function action_get()
    {
        //print_r($_SERVER);
        $request = $this->request->param('request');
        $type = $this->request->param('type');
        $full_request = ($request === '/') ? $request : "$request.$type";

        $file_name = DATAPATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $full_request;

        $mime_type = mime_content_type($file_name);
        $this->response->headers('content-type', $mime_type);
        echo file_get_contents($file_name);


    }

}
