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
        'pending_changes' => 0,
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

    public function getProductByCode($code)
    {
        $row = false;
        try {
            $cache_key = '/' . Model_Product::$_table_name . ':row-code:' . $code;
            $row = Cache::instance('redis')->get($cache_key);
            if (true || empty($row)) {
                $query = DB::select()->from(static::$_table_name)->where('code', '=', $code);
                $row = $query->execute()->as_array();
                if (count($row) === 1) {
                    $row = array_shift($row);
                    $data = Arr::path($row, 'data');
                    $data = json_decode(empty($data) ? '{}' : Arr::path($row, 'data', '{}'), true);
                    unset($data['_id']);
                    $row = array_merge($row, $data);
                    unset($row['data']);
                    $extra_json = Arr::path($row, 'extra_json');
                    $extra_json = json_decode(empty($extra_json) ? '{}' : Arr::path($row, 'extra_json', '{}'), true);
                    unset($extra_json['_id']);
                    $row = array_merge($row, $extra_json);
                    unset($row['extra_json']);

                    Cache::instance('redis')->set($cache_key, json_encode($row));
                    return $row;
                }
            }
        } catch (Exception $e) {
            $error = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            );
            return false;
        } finally {
            return $row;
        }
    }

}
