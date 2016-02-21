<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Category
 */
class Model_Category extends Model_Abstract
{
    protected static $_table_name = 'category';
    protected static $_primary_key = 'categoryid';

    protected static $_columns = array(
        'categoryid' => 0,
        'internal' => 0,
        'hidden' => 0,
        'sequence' => 99999,
        'code' => '',
        'name' => '',
        'name_seo' => '',
        'description' => '',
        'thumb_filepath' => '',
        'image_filepath' => '',
    );

    public function getCategoryByCode($code)
    {
        $row = false;
        try {
            $cache_key = '/' . Model_Category::$_table_name . ':row:' . $code;
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
