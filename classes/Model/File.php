<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_File
 */
class Model_File extends Model_Abstract
{
    protected static $_table_name = 'file';
    protected static $_primary_key = '_id';

    protected static $_columns = array(
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

    protected static $_json_columns = array(
        'tags' => '',
    );

}
