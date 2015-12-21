<?php defined('SYSPATH') or die('No direct script access.');

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

        if (empty($this->_cookie_data['product'])) {
            $this->_cookie_data['product'] = array();
        }
        if (empty($this->_cookie_data['details'])) {
            $this->_cookie_data['details'] = array();
        }

        $model_order = new Model_Order();
        $options = array();
        $data['orderid'] = (int)Arr::path($this->_cookie_data, 'orderid');
        $data['type'] = 'sale';

        $result = $model_order->save($data, $error, $options);
        if (empty($this->_cookie_data['orderid'])) {
            $this->_cookie_data['orderid'] = (int)$result[0];
        }

        foreach ($this->_cookie_data['product'] as $key => $status) {
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

        if (Account::isGuestUser() && (empty($this->_cookie_data['checkout-as-guest']))) {
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


            if (empty($this->_cookie_data['product'])) {
                $this->_cookie_data['product'] = array();
            }
            if (empty($this->_cookie_data['details'])) {
                $this->_cookie_data['details'] = array();
            }
            $product_array = array(
                'rows' => array(),
            );

            $order_array = array(
                'orderid' => Arr::path($this->_cookie_data, 'orderid', 0),
            );
            $total = 0;
            $shipping = 0;
            $tax = 0;
            $discount = 0;
            foreach ($this->_cookie_data['product'] as $key => $status) {
                $product_data = Model_Product::getDataById($key);

                $product_array['rows'][$key] = $product_data;

                $total += $product_data['price'];
                $order_array['product'][] = $product_data['productid'];
            }

            $data = array(
                'type' => empty($this->_cookie_data['type']) ? 'sale' : filter_var($this->_cookie_data['type'],
                    FILTER_SANITIZE_STRING),
                'accountid' => empty(self::$account['accountid']) ? -1 : self::$account['accountid'],
                'contact_email' => empty(self::$account['username']) ? 'guest' : self::$account['username'],
                'total' => $total,
                'shipping' => $shipping,
                'tax' => $tax,
                'discount' => $discount,
            );
            if (!empty($this->_cookie_data['orderid'])) {
                $data['orderid'] = (int)$this->_cookie_data['orderid'];
            }
            $errors = false;
            $result = Model_Order::saveRow($data, $errors);

            // Check orders details
            $order_detail_array = empty($this->_cookie_data['order_detail']) ? array() : $this->_cookie_data['order_detail'];

            $order_detail_array['total'] = $total;
            $order_detail_array['shipping'] = $shipping;
            $order_detail_array['tax'] = $tax;
            $order_detail_array['discount'] = $discount;

            if (!empty($result['orderid'])) {
                $this->_cookie_data['orderid'] = $result['orderid'];
                $order_detail_array['orderid'] = $result['orderid'];

                $detail_result = Model_OrderDetail::getDataByParentId($result['orderid']);
                // Store Detailed information
                $errors_detail = array();
                if (isset($this->_cookie_data['products']) && is_array($this->_cookie_data['products'])) {
                    foreach ($this->_cookie_data['products'] as $productid => $product_status) {
                        $detailid = isset($this->_cookie_data['details'][$productid]) ? $this->_cookie_data['details'][$productid] : 0;

                        if (!$product_data = Model_Product::getDataById($productid)) {
                            unset($this->_cookie_data['details'][$productid]);
                        }
                        if ($detailid && (!$check_detail_exists = Model_OrderDetail::getDataById($detailid))) {
                            unset($this->_cookie_data['details'][$productid]);
                        }

                        $detail_data = array(
                            'detailid' => $detailid,
                            'orderid' => $this->_cookie_data['orderid'],
                            'productid' => $productid,
                            'description' => empty($product_data['description']) ? '' : $product_data['description'],
                        );
                        $update_detail = Model_OrderDetail::saveRow($detail_data, $errors_detail, array(
                            'no_extra_json' => true,
                        ));

                        $this->_cookie_data['details'][$productid] = $update_detail['detailid'];
                        $order_detail_array['details'][$productid] = $detail_data;
                    }
                }
            }
            View::bind_global('product_array', $product_array);
            View::bind_global('order_array', $order_array);
            View::bind_global('order_detail_array', $order_detail_array);
        }

        $data = Account::factory()->profile();

        View::bind_global('account_data', $data);

        View::bind_global('shopping_cart_data', $shopping_cart_data);
        View::bind_global('main', $main);
    }

    public function action_ajax_guest_checkout()
    {
        $this->output['POST'] = $this->json;

        $this->_cookie_data['checkout-as-guest'] = 1;
    }

    public function action_ajax_cashstore()
    {
        $this->output['POST'] = $this->json;

        $this->_cookie_data['action_ajax_cashstore'] = 1;
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
            $product_array = Model_Product::getDataByParentId($categoryData['categoryid'], $filters,
                $this->_cookie_data[Constants::LIMIT], $this->_cookie_data[Constants::OFFSET]);
            $this->page_title = $categoryData['name'];

            $product_array = Product::setReservedField($product_array, $this->_cookie_data, static::$account);

            $pagination_array = array(
                'count' => $product_array['count'],
                'pages' => $product_array['pages'],
                'limit' => $product_array['limit'],
            );
        }
        $picture = new Model_Picture();
        $sort = array();

        $picture_limit = 1000;
        $picture_offset = 0;
        if (isset($product_array['rows'])) {
            $filter_picture = array();
            $picture_array = $picture->filter($filter_picture, $sort, $picture_limit, $picture_offset);
            $main = 'shopping/browse';
        } else {
            $product_array['reserved'] = 'cart' . static::$account['accountid'];
            $filter_picture = array(
                array('productid', '=', $productId),
            );
            $picture_array = $picture->filter($filter_picture, $sort, $picture_limit, $picture_offset);
            $product_array['thumbnail_detail'] = $picture_array['rows'][$product_array['thumbnailid']];
            $main = 'shopping/product_detail';
        }

        foreach ($picture_array['rows'] as $key=>$picture) {
            $picture_array['rows'][$key]['full_url'] = URL::Site(Route::get('image-actions')->uri(array(
                'action' => 'resize',
                'width' => '1024',
                'height' => '1024',
                'method' => 'crop',
                'pictureid' => $key,
                'request' => $picture['md5_hash'],
                'type' => 'jpg',
            )), true);
        }

        View::bind_global('categoryData', $categoryData);
        View::bind_global('product_array', $product_array);
        View::bind_global('picture_array', $picture_array);
        View::bind_global('pagination_array', $pagination_array);
        View::bind_global('main', $main);
    }

    public function action_ajax_add()
    {
        //$json = file_get_contents('php://input');
        //$values = json_decode($json, true);

        $result = array();

        $product_id = (int)$_POST['productId'];
        $this->_cookie_data['product'][$product_id] = 'added';

        $result['cart'] = $this->_cookie_data;

        $result['error'] = in_array($product_id, $this->_cookie_data);
        $result['cookie_data'] = $this->_cookie_data;

        $this->output = $result;
    }

    public function action_ajax_remove()
    {
        $result = array();

        $product_id = (int)$_POST['productId'];
        unset($this->_cookie_data['product'][$product_id]);

        $result['cart'] = $this->_cookie_data;

        $result['error'] = in_array($product_id, $this->_cookie_data);

        $this->output = $result;
    }


    public function action_charge()
    {
        $this->auto_render = false;

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

        $this->output['message'] = '<h1>Successfully charged $50.00!</h1>';
    }

    public function action_ajax_charge()
    {
        $this->output['message'] = 'Thanks for your purchase';
        $this->output['_POST'] = $_POST;

        $orderid = filter_var(Arr::path($_POST, 'orderid', 0), FILTER_SANITIZE_NUMBER_INT);

        // Get order info
        $model_order = new Model_Order();
        $order_data = $model_order->getDataById($orderid);

        // Get account info
        $model_account = new Model_Account();
        $account_data = $model_account->getDataById($order_data['accountid']);


        $this->output['$order_data'] = $order_data;


        $stripe_token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $order_data['total'] * 100, // amount in cents, again
                "currency" => "usd",
                "source" => $stripe_token,
                "description" => 'Order #' . $order_data['orderid'] . ' by ' . $order_data['contact_email'],
            ));
            $this->output['charge'] = $charge->__toArray(true);

            $model_stripe = new Model_Stripe();

            $charge_data = $charge->__toArray(true);
            $charge_data['transaction_id'] = $charge_data['id'];

            $order_data['stripe_transaction'] = $charge_data['id'];
            $order_data['stripe_paid'] = $charge_data['paid'];
            $order_data['stripe_amount'] = $charge_data['amount'];

            $order_data['shipping_name'] = $account_data['display_name'];
            $order_data['shipping_address1'] = $account_data['shipping_address1'];
            $order_data['shipping_address2'] = $account_data['shipping_address2'];
            $order_data['shipping_city'] = $account_data['shipping_city'];
            $order_data['shipping_state'] = $account_data['shipping_state'];
            $order_data['shipping_postal_code'] = $account_data['shipping_postal_code'];
            $order_data['shipping_country'] = $account_data['shipping_country'];

            if ($order_data['total'] == ($charge_data['amount'] / 100)) {
                $order_data['status'] = 'paid';
            }

            $errors = false;
            $model_stripe->save($charge_data, $errors);


            $queue_name = 'DEV::stripe::charge';
            $data = array(
                'account' => $order_data['contact_email'],
                'orderid' => $order_data['orderid'],
            );
            $result = Queue::queueMessage($queue_name, $data);

            $errors = false;
            $model_order->save($order_data, $errors);

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
        if (empty($this->_cookie_data['product'])) {
            $this->_cookie_data['product'] = array();
        }
        if (empty($this->_cookie_data['details'])) {
            $this->_cookie_data['details'] = array();
        }

        $sub_total = 0;
        $tax = 0;
        $shipping = 0;
        $product_array = array();

        foreach ($this->_cookie_data['product'] as $key => $status) {
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
