<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/21/2015
 * Time: 4:25 PM
 */

class Model_OrderbDetail extends Model_Abstract
{
    protected static $_table_name = 'orderb_detail';
    protected static $_primary_key = 'detailid';
    protected static $_columns = array(
        'detailid' => 0,
        'orderid' => 0,
        'productid' => 0,
        'description' => '',
        'price' => 0,
    );

    public static $_json_columns = array(
    );

}
