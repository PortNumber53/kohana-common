<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Common_Core_Product extends Controller_Website
{
    public function action_browse()
    {
        $main = 'product/browse';
        View::bind_global('main', $main);

        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $product_array = Product::filter($filter);
        View::bind_global('product_array', $product_array);
    }

    public function action_edit()
    {
        $object_id = $this->request->param('id');
        $product_data = Product::get_by_object_id($object_id);
        if (!$product_data) {
            $product_data = Product::getEmptyRow();
        }
        View::bind_global('product_data', $product_data);

        $main = 'product/edit';
        View::bind_global('main', $main);

        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $product_array = Product::filter($filter);
        View::bind_global('product_array', $product_array);
    }

    public function action_ajax_edit()
    {
        $this->output = array(
            'posted' => $_POST,
        );
        $error = FALSE;
        $object_id = empty($_POST['object_id']) ? $this->request->param('id') : (int)$_POST['object_id'];
        if (empty($object_id)) {
            $object_id = Model_Sequence::nextval();
        }
        $product_data = array(
            '_id' => '/' . DOMAINNAME . '/' . $object_id . '/' . URLify::filter($_POST['name'], '-', TRUE),
            'object_id' => $object_id,
            'categoryid' => 0,
            'status' => $_POST['status'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'tags' => $_POST['tags'],
        );
        $result = Product::update($product_data, $error);

        if ($result) {
            $this->output['redirect_url'] = URL::Site(route::get('default')->uri(array('controller' => 'product', 'action' => 'edit', 'id' => $object_id)), TRUE);
            $this->output['message'] = __('Product information updated successfully');
            $this->output['dismiss_timer'] = 2;
        }
        $this->output['error'] = $error;
        $this->output['result'] = $result;
    }
}
