<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 11/16/13
 * Time: 1:09 PM
 * Something meaningful about this file
 *
 */
class Model_File extends Model_Abstract
{
    public static $_table_name = 'file';
    public static $_primary_key = '_id';

    public static $_columns = array(
        '_id' => '',
        'object_id' => 0,
        'category_id' => 0,
        'status' => '',
        'name' => '',
        'description' => '',
        'created_at' => '',
        'modified_at' => '',
        'extra_json' => '{}',
    );

    public static $_json_columns = array(
        'tags' => '',
    );

}
