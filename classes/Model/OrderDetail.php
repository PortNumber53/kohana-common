<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_OrderDetail
 */
class Model_OrderDetail extends Model_Abstract
{
    protected static $_table_name = 'order_detail';
    protected static $_primary_key = 'detailid';
    protected static $_columns = array(
        'detailid' => 0,
        'orderid' => 0,
        'productid' => 0,
        'description' => '',
        'price' => 0,
    );

    protected static $_json_columns = array();

}
