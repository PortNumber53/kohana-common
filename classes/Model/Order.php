<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Order
 */
class Model_Order extends Model_Abstract
{
    protected static $_table_name = 'order';
    protected static $_primary_key = 'orderid';
    protected static $_columns = array(
        'orderid' => 0,
        'type' => '',
        'status' => '',
        'accountid' => 0,
        'contact_email' => '',
        'total' => '',
        'shipping' => '',
        'tax' => '',
        'discount' => '',
        'shipping_name' => '',
        'shipping_address1' => '',
        'shipping_address2' => '',
        'shipping_city' => '',
        'shipping_state' => '',
        'shipping_postal_code' => '',
        'shipping_country' => '',
        'created_at' => '',
        'updated_at' => '',
    );

    public static $_json_columns = array();

}
