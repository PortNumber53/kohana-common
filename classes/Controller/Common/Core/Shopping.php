<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/9/2015
 * Time: 9:11 AM
 */
class Controller_Common_Core_Shopping extends Controller_Website
{

    private $_category_array = null;
    private $_picture_array = null;

    public function before()
    {
        parent::before();


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


        View::bind_global('stripe', self::$settings['stripe']);
        \Stripe\Stripe::setApiKey(self::$settings['stripe']['secret_key']);
    }

    public function after()
    {
        Cookie::set('site', json_encode($this->_cookie));

        parent::after();
    }


    public function generate_txt()
    {
        $page = Request::current()->param('page', '');
        $limit = 100;
        $offset = $page * $limit;;
        $sort = array(
            'object_id' => 'ASC',
        );

        $product = new Model_Product();
        $sort = array();
        //$limit = 0;
        //$offset = 0;
        $filter = array(
            array('status', '=', 'available'),
        );
        $product_array = $product->filter($filter, $sort, $limit, $offset);

        $data = array();

        if (empty($page)) {
            // Show list of pages for this map
            $pages = $product_array['count'] / $limit;
            for ($i = 0; $i < $pages; $i++) {
                $data[] = array(
                    'url' => URL::Site(Route::get('sitemap')->uri(array(
                        'name' => 'shopping',
                        'page' => $i,
                        'format' => 'txt',
                    )), true),
                );
            }
        } else {
            foreach ($product_array['rows'] as $row) {

                $data[] = array(
                    'url' => URL::Site(Route::get('default')->uri(array(
                        'controller' => 'shopping',
                        'action' => 'browse',
                        'id' => strtolower('item_' . $row['productid'] . '-' . $row['name_seo']),
                    )), true),
                );
            }
        }
        return $data;
    }


    public function action_cart()
    {
        $this->page_title = 'Shopping cart';
        $main = 'shopping/cart';


        if (empty($this->_cookie['product'])) {
            $this->_cookie['product'] = array();
        }
        if (empty($this->_cookie['details'])) {
            $this->_cookie['details'] = array();
        }

        $product_array = array(
            'rows' => array(),
        );
        foreach ($this->_cookie['product'] as $key => $status) {
            $product_data = Model_Product::getDataById($key);

            $product_array['rows'][$key] = $product_data;
        }

        View::bind_global('main', $main);
        View::bind_global('product_array', $product_array);
    }

    public function action_checkout()
    {
        $shopping_cart_data = $this->shoppingCartData();

        $this->page_title = 'Checkout';

        if (Account::isGuestUser() && (empty($this->_cookie['checkout-as-guest']))) {
            $main = 'shopping/guest_user';
        } else {

            $main = 'shopping/checkout';


            $picture = new Model_Picture();
            $sort = array();
            $limit = 0;
            $offset = 0;
            $filter = array(//array('status', '=', 'import'),
            );
            $picture_array = $picture->filter($filter, $sort, $limit, $offset);
            View::bind_global('picture_array', $picture_array);


            if (empty($this->_cookie['product'])) {
                $this->_cookie['product'] = array();
            }
            if (empty($this->_cookie['details'])) {
                $this->_cookie['details'] = array();
            }
            $product_array = array(
                'rows' => array(),
            );
            //echo'<pre>';print_r($this->_cookie);echo'</pre>';

            $total = 0;
            $shipping = 0;
            $tax = 0;
            $discount = 0;
            foreach ($this->_cookie['product'] as $key => $status) {
                $product_data = Model_Product::getDataById($key);

                $product_array['rows'][$key] = $product_data;

                $total += $product_data['price'];
            }

            $data = array(
                'type' => empty($this->_cookie['type']) ? 'sale' : filter_var($this->_cookie['type'],
                    FILTER_SANITIZE_STRING),
                'accountid' => empty(self::$account['accountid']) ? -1 : self::$account['accountid'],
                'contact_email' => empty(self::$account['username']) ? 'guest' : self::$account['username'],
                'total' => $total,
                'shipping' => $shipping,
                'tax' => $tax,
                'discount' => $discount,
            );
            if (!empty($this->_cookie['orderid'])) {
                $data['orderid'] = (int)$this->_cookie['orderid'];
            }
            $result = Model_Orderb::saveRow($data, $errors);
            //$data['orderid'] = (int)$result['orderid'];
            //echo'<pre>';print_r($result);echo'</pre>';

            // Check orders details
            $order_detail_array = empty($this->_cookie['order_detail']) ? array() : $this->_cookie['order_detail'];

            $order_detail_array['total'] = $total;
            $order_detail_array['shipping'] = $shipping;
            $order_detail_array['tax'] = $tax;
            $order_detail_array['discount'] = $discount;

            if (!empty($result['orderid'])) {
                $this->_cookie['orderid'] = $result['orderid'];
                $order_detail_array['orderid'] = $result['orderid'];


                $detail_result = Model_OrderbDetail::getDataByParentId($result['orderid']);
                // Store Detailed information
                $errors_detail = array();
                foreach ($this->_cookie['product'] as $productid => $product_status) {
                    $detailid = isset($this->_cookie['details'][$productid]) ? $this->_cookie['details'][$productid] : 0;

                    if (!$product_data = Model_Product::getDataById($productid)) {
                        unset($this->_cookie['details'][$productid]);
                    }
                    if ($detailid && (!$check_detail_exists = Model_OrderbDetail::getDataById($detailid))) {
                        unset($this->_cookie['details'][$productid]);
                    }

                    $detail_data = array(
                        'detailid' => $detailid,
                        'orderid' => $this->_cookie['orderid'],
                        'productid' => $productid,
                        'description' => empty($product_data['description']) ? '' : $product_data['description'],
                    );
                    $update_detail = Model_OrderbDetail::saveRow($detail_data, $errors_detail);

                    $this->_cookie['details'][$productid] = $update_detail['detailid'];
                    //echo'<pre>';print_r($this->_cookie['details']);echo'</pre>';
                    $order_detail_array['details'][$productid] = $detail_data;
                }

            }
            View::bind_global('product_array', $product_array);
            View::bind_global('order_array', $order_array);
            View::bind_global('order_detail_array', $order_detail_array);
        }


        View::bind_global('shopping_cart_data', $shopping_cart_data);
        View::bind_global('main', $main);
    }

    public function action_ajax_guest_checkout()
    {
        $this->output['POST'] = $this->json;

        $this->_cookie['checkout-as-guest'] = 1;
    }


    public function action_wishlist()
    {
        $this->page_title = 'Wishlist';
        $main = 'shopping/wishlist';

        View::bind_global('main', $main);
    }

    public function action_browse()
    {
        $categorySeo = '';
        $categoryData = false;
        $product_array = false;
        $pagination_array = array();

        $request = $this->request->param('id');

        $cookie_limit = empty(Arr::path($this->_cookie, Constants::LIMIT, 9)) ? 9 : Arr::path($this->_cookie,
            Constants::LIMIT, 9);

        $limit = empty($this->request->query(Constants::LIMIT)) ? $cookie_limit : $this->request->query(Constants::LIMIT);
        $offset = empty($this->request->query(Constants::OFFSET)) ? 0 : $this->request->query(Constants::OFFSET);
        $this->_cookie[Constants::LIMIT] = $this->_per_page = $limit;
        $this->_cookie[Constants::OFFSET] = $offset;

        $this->_cookie = json_decode(Cookie::get('cart'), true);


        // View::bind_global('picture_array', $picture_array);

        $pattern = '/item_([0-9]+)([-])([a-z0-9]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $productId = $matches[1];
            $product_array = Model_Product::getDataById($productId);
            $this->page_title = $product_array['name'];
            //$categoryData = Model_Category::getDataById($product_array['categoryid']);

            //$pictureData = Model_Picture::getDataByParentId($product_array['productid']);
        }

        $pattern = '/cat_([a-z\-]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $categorySeo = $matches[1];
        }

        if (!empty($categorySeo)) {
            $categoryData = Model_Category::getDataBySeo($categorySeo);
            $filters = array(
                array('status', '=', 'available'),
            );
            $product_array = Model_Product::getDataByParentId($categoryData['categoryid'], $filters, $limit, $offset);
            $this->page_title = $categoryData['name'];

            foreach ($product_array['rows'] as $key => $product) {
                if (isset($this->_cookie['product'][$key])) {
                    $product_array['rows'][$key]['reserved'] = 'cart' . static::$account['accountid'];
                } else {
                    $product_array['rows'][$key]['reserved'] = '';
                }
            }
            $pagination_array = array(
                'count' => $product_array['count'],
                'pages' => $product_array['pages'],
                'limit' => $product_array['limit'],
            );
        }
        $picture = new Model_Picture();
        $sort = array();
        //$limit = 0;
        //$offset = 0;

        if (isset($product_array['rows'])) {
            $filter_picture = array(
            );
            $picture_array = $picture->filter($filter_picture, $sort, $limit, $offset);
            $main = 'shopping/browse';
        } else {
            $filter_picture = array(
                array('productid', '=', $productId),
            );
            $picture_array = $picture->filter($filter_picture, $sort, $limit, $offset);
            $main = 'shopping/product_detail';
        }
        View::bind_global('categoryData', $categoryData);
        View::bind_global('product_array', $product_array);
        View::bind_global('picture_array', $picture_array);
        View::bind_global('pagination_array', $pagination_array);
        View::bind_global('main', $main);

        View::bind_global('offset', $offset);
    }

    public function action_ajax_add()
    {
        //$json = file_get_contents('php://input');
        //$values = json_decode($json, true);

        $result = array();

        $product_id = (int)$_POST['productId'];
        $this->_cookie['product'][$product_id] = 'added';

        $result['cart'] = $this->_cookie;

        $result['error'] = in_array($product_id, $this->_cookie);
        $result['cookie_data'] = $this->_cookie;

        $this->output = $result;
    }

    public function action_ajax_remove()
    {
        $result = array();

        $product_id = (int)$_POST['productId'];
        unset($this->_cookie['product'][$product_id]);

        $result['cart'] = $this->_cookie;

        $result['error'] = in_array($product_id, $this->_cookie);

        $this->output = $result;
    }


    public function action_charge()
    {
        $token = filter_var($_POST['stripeToken'], FILTER_SANITIZE_STRING);

        $customer = \Stripe\Customer::create(array(
            'email' => 'customer@example.com',
            'card' => $token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount' => 5000,
            'currency' => 'usd'
        ));

        echo '<h1>Successfully charged $50.00!</h1>';
    }

    public function action_ajax_charge()
    {
        $this->output['message'] = 'Thanks for your purchase';
        $this->output['_POST'] = $_POST;

        $stripe_token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // amount in cents, again
                "currency" => "usd",
                "source" => $stripe_token,
                "description" => "Example charge"
            ));
            $this->output['charge'] = $charge;
        } catch (\Stripe\Error\Card $e) {
            // The card has been declined
        }

    }

    public function action_thanks()
    {
        $main = 'shopping/thanks';

        View::bing_global('main', $main);
    }


    public function action_stripehook()
    {
        $this->auto_render = false;
        $this->response->headers('content-type', 'text/plain');

        $result = json_encode(array_merge($_POST, $_GET));

        mail('testing@portnumber53.com', 'Stripe hook - ' . date('Y-m-d'), $result);
        echo "OK";
    }


    private function shoppingCartData()
    {
        if (empty($this->_cookie['product'])) {
            $this->_cookie['product'] = array();
        }
        if (empty($this->_cookie['details'])) {
            $this->_cookie['details'] = array();
        }

        $sub_total = 0;
        $tax = 0;
        $shipping = 0;
        $product_array = array();

        foreach ($this->_cookie['product'] as $key => $status) {
            $product_data = Model_Product::getDataById($key);

            $product_array['rows'][$key] = $product_data;
            $sub_total += $product_data['price'];
        }

        $shopping_cart = array(
            'sub_total' => $sub_total,
            'total' => $sub_total + $shipping + $tax,
            'shipping' => 0,
            'tax' => 0,
            'products' => $product_array,
        );

        return $shopping_cart;
    }

}
