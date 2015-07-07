<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/9/2015
 * Time: 9:11 AM
 */
class Controller_Common_Core_Shopping extends Controller_Website
{

    private $_cookie = null;
    private $_category_array = null;
    private $_picture_array = null;

    private $_per_page = 9;

    public function before()
    {
        parent::before();

        $per_page_array = array(
                9 => '9 per page',
                15 => '15 per page',
                21 => '21 per page',
                30 => '30 per page',
        );
        View::bind_global('per_page_array', $per_page_array);
        View::bind_global('per_page', $this->_per_page);

        $this->_cookie = json_decode(Cookie::get('site'), true);

        $category = new Model_Category();
        $sort = array();
        $limit = 0;
        $offset = 0;
        $filter = array(//array('status', '=', 'import'),
        );
        $this->_category_array = $category->filter($filter, $sort, $limit, $offset);
        View::bind_global('category_data', $this->_category_array);

        $picture = new Model_Picture();
        $sort = array();
        $limit = 0;
        $offset = 0;
        $filter = array(//array('status', '=', 'import'),
        );
        $this->_picture_array = $picture->filter($filter, $sort, $limit, $offset);
        View::bind_global('picture_array', $this->_picture_array);
    }

    public function after()
    {
        Cookie::set('site', json_encode($this->_cookie));

        parent::after();
    }

    public function action_cart()
    {
        $main = 'shopping/cart';


        $cookie_cart = json_decode(Cookie::get('cart'), true);
        if (empty($cookie_cart['product'])) {
            $cookie_cart['product'] = array();
        }
        if (empty($cookie_cart['details'])) {
            $cookie_cart['details'] = array();
        }

        $product_array = array(
            'rows' => array(),
        );
        foreach ($cookie_cart['product'] as $key => $status) {
            $product_data = Model_Product::getDataById($key);

            $product_array['rows'][$key] = $product_data;
        }

        View::bind_global('main', $main);
        View::bind_global('product_array', $product_array);
    }

    public function action_checkout()
    {
        $main = 'shopping/checkout';



        $picture = new Model_Picture();
        $sort = array();
        $limit = 0;
        $offset = 0;
        $filter = array(//array('status', '=', 'import'),
        );
        $picture_array = $picture->filter($filter, $sort, $limit, $offset);
        View::bind_global('picture_array', $picture_array);


        $cookie_cart = json_decode(Cookie::get('cart'), true);

        if (empty($cookie_cart['product'])) {
            $cookie_cart['product'] = array();
        }
        if (empty($cookie_cart['details'])) {
            $cookie_cart['details'] = array();
        }
        $product_array = array(
            'rows' => array(),
        );
        //echo'<pre>';print_r($cookie_cart);echo'</pre>';

        $total = 0;
        $shipping = 0;
        $tax = 0;
        $discount = 0;
        foreach ($cookie_cart['product'] as $key => $status) {
            $product_data = Model_Product::getDataById($key);

            $product_array['rows'][$key] = $product_data;

            $total += $product_data['price'];
        }

        $data = array(
            'type' => empty($cookie_cart['type']) ? 'sale' : filter_var($cookie_cart['type'], FILTER_SANITIZE_STRING),
            'accountid' => empty(self::$account['accountid']) ? -1 : self::$account['accountid'],
            'contact_email' => empty(self::$account['username']) ? 'guest' : self::$account['username'],
            'total' => $total,
            'shipping' => $shipping,
            'tax' => $tax,
            'discount' => $discount,
        );
        if (!empty($cookie_cart['orderid'])) {
            $data['orderid'] = (int)$cookie_cart['orderid'];
        }
        $result = Model_Orderb::saveRow($data, $errors);
        //$data['orderid'] = (int)$result['orderid'];
        //echo'<pre>';print_r($result);echo'</pre>';

        // Check orders details
        $order_detail_array = empty($cookie_cart['order_detail']) ? array() : $cookie_cart['order_detail'];

        $order_detail_array['total'] = $total;
        $order_detail_array['shipping'] = $shipping;
        $order_detail_array['tax'] = $tax;
        $order_detail_array['discount'] = $discount;

        if (!empty($result['orderid'])) {
            $cookie_cart['orderid'] = $result['orderid'];
            $order_detail_array['orderid'] = $result['orderid'];


            $detail_result = Model_OrderbDetail::getDataByParentId($result['orderid']);
            // Store Detailed information
            $errors_detail = array();
            foreach ($cookie_cart['product'] as $productid => $product_status) {
                $detailid = isset($cookie_cart['details'][$productid]) ? $cookie_cart['details'][$productid] : 0;

                if (!$product_data = Model_Product::getDataById($productid)) {
                    unset($cookie_cart['details'][$productid]);
                }
                if ($detailid && (!$check_detail_exists = Model_OrderbDetail::getDataById($detailid))) {
                    unset($cookie_cart['details'][$productid]);
                }

                $detail_data = array(
                    'detailid' => $detailid,
                    'orderid' => $cookie_cart['orderid'],
                    'productid' => $productid,
                    'description' => empty($product_data['description']) ? '' : $product_data['description'],
                );
                $update_detail = Model_OrderbDetail::saveRow($detail_data, $errors_detail);

                $cookie_cart['details'][$productid] = $update_detail['detailid'];
                //echo'<pre>';print_r($cookie_cart['details']);echo'</pre>';
                $order_detail_array['details'][$productid] = $detail_data;
            }

        }
        Cookie::set('cart', json_encode($cookie_cart));

        View::bind_global('main', $main);
        View::bind_global('product_array', $product_array);
        View::bind_global('order_array', $order_array);
        View::bind_global('order_detail_array', $order_detail_array);
    }

    public function action_wishlist()
    {
        $main = 'shopping/wishlist';

        View::bind_global('main', $main);
    }

    public function action_browse()
    {
        $categorySeo = '';
        $categoryData = false;
        $productData = false;

        $request = $this->request->param('id');

        $limit = empty($this->request->query(Constants::LIMIT)) ? $this->_cookie[Constants::LIMIT] : $this->request->query(Constants::LIMIT);
        $offset = empty($this->request->query(Constants::OFFSET)) ? $this->_cookie[Constants::OFFSET] : $this->request->query(Constants::OFFSET);

        echo '<pre>'. $limit .'</pre>';
        $this->_cookie[Constants::LIMIT] = $this->_per_page = $limit;
        $this->_cookie[Constants::OFFSET] = $offset;

        $picture = new Model_Picture();
        $sort = array();
        //$limit = 0;
        //$offset = 0;
        $filter = array(//array('status', '=', 'import'),
        );
        $picture_array = $picture->filter($filter, $sort, $limit, $offset);
        View::bind_global('picture_array', $picture_array);

        $pattern = '/item_([0-9]+)([-])([a-z0-9]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $productId = $matches[1];
            $productData = Model_Product::getDataById($productId);
            //$categoryData = Model_Category::getDataById($productData['categoryid']);

            //$pictureData = Model_Picture::getDataByParentId($productData['productid']);

            $cookie_cart = json_decode(Cookie::get('cart'), true);
            if (isset($cookie_cart['product'][$productId])) {
                $productData['reserved'] = 'cart' . static::$account['accountid'];
                //$productData['status'] = '';
            } else{
                $productData['reserved'] = '';
            }
        }

        $pattern = '/cat_([a-z\-]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $categorySeo = $matches[1];
        }

        if (!empty($categorySeo)) {
            $categoryData = Model_Category::getDataBySeo($categorySeo);
            $productData = Model_Product::getDataByParentId($categoryData['categoryid'], $limit, $offset);
        }

        if (isset($productData['rows'])) {
            $main = 'shopping/browse';
        } else {
            $main = 'shopping/product_detail';
        }
        View::bind_global('categoryData', $categoryData);
        View::bind_global('product_array', $productData);
       // View::bind_global('picture_array', $pictureData);
        View::bind_global('main', $main);
    }

    public function action_ajax_add()
    {
        $json = file_get_contents('php://input');
        $values = json_decode($json, true);
        //var_dump($values);

        //var_dump($_POST);
        $result = array();
        $cookie_cart = json_decode(Cookie::get('cart'), true);

        $product_id = (int)$_POST['productId'];
        $cookie_cart['product'][$product_id] = 'added';

        $result['cart'] = $cookie_cart;

        Cookie::set('cart', json_encode($cookie_cart));
        $result['error'] = in_array($product_id, $cookie_cart);

        $this->output = $result;
    }

    public function action_ajax_remove()
    {
        $json = file_get_contents('php://input');
        $values = json_decode($json, true);
        //var_dump($values);

        //var_dump($_POST);
        $result = array();
        $cookie_cart = json_decode(Cookie::get('cart'), true);

        $product_id = (int)$_POST['productId'];
        unset($cookie_cart['product'][$product_id]);

        $result['cart'] = $cookie_cart;

        Cookie::set('cart', json_encode($cookie_cart));
        $result['error'] = in_array($product_id, $cookie_cart);

        $this->output = $result;
    }
}
