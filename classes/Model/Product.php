<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 10/27/13
 * Time: 6:01 PM
 * Something meaningful about this file
 *
 */
class Model_Product extends Model_Abstract
{
    protected static $_table_name = 'product';
    protected static $_primary_key = 'productid';
    protected static $_columns = array(
        'productid' => 0,
        'categoryid' => 0,
        'status' => '',
        'keywords' => '',
        'sequence' => 999999,
        'name' => '',
        'name_seo' => '',
        'code' => '',
        'description' => '',
        'price' => 0,
        'featured' => 0,
        'shipping' => 0,
        'weight' => 0,
        'width' => 0,
        'height' => 0,
        'depth' => 0,
        'thumbnailid' => 0,
        'quantity' => 0,
        'created' => 0,
        'updated' => 0,
        'purchased' => 0,
        'folder' => '',
        'thumb_filepath' => '',
        'image_filepath' => '',
    );

    public static $_json_columns = array(
        'tags' => '',
    );

    public static function _getDataByParentId($parentId, $filters=array(), $limit, $offset)
    {
        $product = new Model_Product();
        $sort = array(
            'name' => 'ASC',
        );
        $filter = array(
            array('categoryid', '=', $parentId,),
        );
        $filters = array_merge($filters, $filter);
        $product_result = $product->filter($filters, $sort, $limit, $offset);

        return $product_result;
    }

}
