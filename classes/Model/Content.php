<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 5/17/13
 * Time: 1:26 AM
 *
 */
class Model_Content extends Model_Abstract
{
    public static $_table_name = 'content';
    public static $_primary_key = 'contentid';

    public static $_columns = array(
        'contentid' => '',
        'object_id' => 0,
        'author_id' => '',
        'url' => '',
        'mimetype' => '',
        'title' => '',
        'created_at' => '',
        'modified_at' => '',
        'extra_json' => '',
    );

    public static function getDataByUrl($url)
    {
        $content = new Model_Content();
        $limit = 1;
        $offset = 0;
        $sort = array();
        $filters = array(
            array('url', '=', $url,),
        );
        $product_result = $content->filter($filters, $sort, $limit, $offset);

        if (count($product_result['rows']) == 1) {
            return array_pop($product_result['rows']);
        } else {
            return false;
        }
    }

    public function get_by_account_id($account_id)
    {
        $query = DB::select()->from(self::$_table_name)->where('account_id', '=', $account_id);
        $result_set = $query->execute()->as_array();
        if (count($result_set) > 0) {
            foreach ($result_set as $key => &$item) {
                $array = json_decode($result_set[$key]['data'], true);
                unset($result_set[$key]['data']);
                $result_set[$key] = array_merge($result_set[$key], $array);
            }
            return $result_set;
        } else {
            return false;
        }
    }

}
