<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Service_Core_Product
 */
class Controller_Service_Core_Product extends Controller_Service_Core_Service
{

    public function action_ajax_delete()
    {
        $error = false;
        $this->output = array(
            'posted' => $_POST,
        );

        $result = Product::delete_by_object_id($_POST['what'], $error);
        if ($result) {
            $this->output['redirect_url'] = $_POST['back_url'];
        }

        $this->output['result'] = $result;
        $this->output['error'] = $error;

        $filter = array();
        $product_array = Product::filter($filter);
        View::set_global('product_array', $product_array);
        $this->output['table_body'] = View::factory('product/ajax_browse')->render();
    }
}
