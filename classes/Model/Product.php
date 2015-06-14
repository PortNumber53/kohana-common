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

    public static function _getDataById($id)
    {
        $cache_key = '/' . static::$_table_name . ':row:' . $id;
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where(static::$_primary_key, '=', $id);
            $row = $query->execute()->as_array();
            if (count($row) == 1) {
                $row = array_shift($row);
                $data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), true);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row, 'extra_json',
                    '{}'), true);
                unset($extra_json['_id']);
                $row = array_merge($row, $extra_json);
                unset($row['extra_json']);

                Cache::instance('redis')->set($cache_key, json_encode($row));
                return $row;
            }
        } else {
            $row = json_decode($row, true);
        }
        return $row;
    }

    public static function _getDataByParentId($parentId, $limit, $offset)
    {
        $product = new Model_Product();
        $sort = array(
            'name' => 'ASC',
        );
        $filter = array(
            array('categoryid', '=', $parentId,),
        );
        $product_result = $product->filter($filter, $sort, $limit, $offset);

        return $product_result;
    }
}
