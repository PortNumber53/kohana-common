<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 7/11/2015
 * Time: 2:07 AM
 */

class Controller_Backend_Core_Product extends Controller_Backend_Core_Backend
{

    public function action_edit()
    {
        $main = 'backend/product/edit';

        $request = $this->request->param('request');

        $pattern = '/item_([0-9]+)([-])([a-z0-9]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $productId = $matches[1];
            $product_array = Model_Product::getDataById($productId);
            $product_array['categoryid'] = (int) $product_array['categoryid'];
            $pictures = Model_Picture::getDataByParentId($product_array['productid']);
            $product_array['pictures'] = empty($pictures) ? array() : $pictures['rows'];
        }



        // Get categories
        $category        = new Model_Category();
        $sort           = array(
            'name'   => 'asc',
        );
        $limit          = 0;
        $offset         = 0;
        $filter         = array();
        $category_array = $category->filter($filter, $sort, $limit, $offset);
        View::bind_global('category_array', $category_array);


        View::bind_global('product_array', $product_array);
        View::bind_global('picture_array', $picture_array);
        View::bind_global('main', $main);
    }


    public function action_main()
    {
        $main = 'backend/product/main';

        $request = $this->request->param('request');
        $params = explode(':', $request);

        // Get categories
        $category        = new Model_Category();
        $sort           = array(
            'name'   => 'asc',
        );
        $limit          = 0;
        $offset         = 0;
        $filter         = array();
        $category_result = $category->filter($filter, $sort, $limit, $offset);
        View::bind_global('category_data', $category_result);

        $page = 1;
        if (isset($params[0]) && $params[0] == 'page') {
            $page = (int) $params[1];
        }

        $product        = new Model_Product();
        $sort           = array(
            'status' => 'asc',
            'name'   => 'asc',
        );
        $limit          = 25;
        $offset         = ($page-1) * $limit;
        $filter         = array();
        $product_array  = $product->filter($filter, $sort, $limit, $offset);
        View::bind_global('product_array', $product_array);


        $picture        = new Model_Picture();
        $sort           = array();
        $limit          = 0;
        $offset         = 0;
        $filter         = array(//array('status', '=', 'import'),
        );
        $picture_array = $picture->filter($filter, $sort, $limit, $offset);
        View::bind_global('picture_array', $picture_array);


        View::bind_global('page', $page);
        View::bind_global('main', $main);
    }


    public function action_ajax_status()
    {
        $this->output = array(
            'POST' => $_POST,
        );

        $error = array();
        $data = array();

        $data['productid'] = (int) $this->request->post('productId');

        if (!empty($this->request->post('newStatus'))) {
            $data['status'] = filter_var($this->request->post('newStatus'), FILTER_SANITIZE_STRING);
        }

        $product = new Model_Product();
        $data['updated'] = date('Y-m-d H:i:s', time());

        if (! empty($data)) {
            $product->save($data, $error);
            $data['error'] = $error;
        }

        $this->output = array_merge($this->output, $data);
    }


    public function action_ajax_update()
    {
        $errors = array();
        $this->output = array(
            'POST' => $_POST,
        );

        $productId = (int)$this->request->post('id');
        $categoryId = (int)$this->request->post('categoryId');
        $code = filter_var($this->request->post('code'), FILTER_SANITIZE_STRING);
        $name = filter_var($this->request->post('name'), FILTER_SANITIZE_STRING);
        $status = filter_var($this->request->post('status'), FILTER_SANITIZE_STRING);
        $price = filter_var($this->request->post('price'), FILTER_SANITIZE_STRING);
        $description = filter_var($this->request->post('description'), FILTER_SANITIZE_STRING);
        $thumbnailid = filter_var($this->request->post('thumbnailId'), FILTER_SANITIZE_STRING);

        $data = array();

        $product = new Model_Product();
        $data['updated'] = date('Y-m-d H:i:s', time());
        if (!empty($categoryId)) {
            $data['categoryid'] = $categoryId;
        }
        if (!empty($code)) {
            $data['code'] = $code;
        }
        if (!empty($name)) {
            $data['name'] = $name;
        }
        if (!empty($status)) {
            $data['status'] = $status;
        }
        if (!empty($price)) {
            $data['price'] = $price;
        }
        if (!empty($description)) {
            $data['description'] = $description;
        }
        if (!empty($thumbnailid)) {
            $data['thumbnailid'] = $thumbnailid;
        }
        $data['productid'] = $productId;

        $result = Model_Product::saveRow($data, $errors);

        $this->output['newData'] = $data;
        $this->output['productId'] = $productId;
    }



    public function action_ajax_save()
    {
        $errors = array();
        $this->output = array(
            'POST' => $_POST,
        );

        $product_id = (int)$this->request->post('productid');
        $category_id = (int)$this->request->post('categoryid');
        $code = filter_var($this->request->post('code'), FILTER_SANITIZE_STRING);
        $name = filter_var($this->request->post('name'), FILTER_SANITIZE_STRING);
        $status = filter_var($this->request->post('status'), FILTER_SANITIZE_STRING);
        $price = filter_var($this->request->post('price'), FILTER_SANITIZE_STRING);
        $description = filter_var($this->request->post('description'), FILTER_SANITIZE_STRING);
        $thumbnailid = filter_var($this->request->post('thumbnailid'), FILTER_SANITIZE_STRING);

        $data = array();

        $product = new Model_Product();
        $data['updated'] = date('Y-m-d H:i:s', time());
        if (!empty($category_id)) {
            $data['categoryid'] = $category_id;
        }
        if (!empty($code)) {
            $data['code'] = $code;
        }
        if (!empty($name)) {
            $data['name'] = $name;
        }
        if (!empty($status)) {
            $data['status'] = $status;
        }
        if (!empty($price)) {
            $data['price'] = $price;
        }
        if (!empty($description)) {
            $data['description'] = $description;
        }
        if (!empty($thumbnailid)) {
            $data['thumbnailid'] = $thumbnailid;
        }
        $data['productid'] = $product_id;

        $result = Model_Product::saveRow($data, $errors);

        $this->output['newData'] = $data;
        $this->output['productId'] = $product_id;
    }
}