<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 12/27/13
 * Time: 12:54 AM
 * Something meaningful about this file
 *
 */
class Controller_Common_Seo_Sitemap extends Controller_Website
{
    public $auto_render = false;

    public function action_generate()
    {
        $name = ucwords($this->request->param('name'));
        $page = $this->request->param('page');
        $format = $this->request->param('format');

        $url_array = array();

        if (class_exists('Controller_' . $name)) {
            $class_name = 'Controller_' . $name;
            $class = new $class_name($this->request, $this->response);

            $method_name = 'generate_' . $format;
            if (method_exists($class, $method_name)) {
                $url_array = call_user_func_array(array($class, $method_name), array('page' => $page));
            }
        }

        switch ($name) {
            case 'general':
                $content = new Model_Content();
                $data = $content->get_posts(1000, $page, 1000);

                foreach ($data as $each) {
                    $url_array[] = $each['url'];
                }

                break;
            /*
        default:
            $image = new Model_Image();
            $data = $image->get_urls(100, $page, 1000);
            foreach ($data as $each)
            {
                $url_array[] = $each['_id'];
            }
             */
        }
        switch ($format) {
            case "xml":
                //$this->response->headers('Content-type', 'application/rss+xml');
                break;
            default:
                //$this->response->headers('Content-type', 'plain/text');
        }

        View::bind_global('url_array', $url_array);
        $this->response->body(View::factory('seo/sitemap/' . $format)->render());
    }

}