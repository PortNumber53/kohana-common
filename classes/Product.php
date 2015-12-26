<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 10/27/13
 * Time: 6:29 PM
 * Something meaningful about this file
 *
 */
class Product
{
    const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

    protected static $data = array();

    public static $class_name = 'Model_Product';

    public static function factory()
    {
        $obj = new self();
        $obj::$data = array();

        return $obj;
    }

    public static function setReservedField(&$product_array, $cookies, $account)
    {
        foreach ($product_array['rows'] as $key => $product) {
            if (isset($cookies['product'][$key])) {
                $product_array['rows'][$key]['reserved'] = 'cart' . $account['id'];
            } else {
                $product_array['rows'][$key]['reserved'] = '';
            }
        }
        return $product_array;
    }

}
