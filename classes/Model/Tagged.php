<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Tag_Gallery
 */
class Model_Tag_Gallery extends Model_Abstract
{
    public static $_table_name = 'tagged';
    public static $_primary_key = '_id';

    public static $_columns = array(
        '_id' => '',
        'object_id' => 0,
        'associated_id' => 0,
        'created_at' => '',
        'modified_at' => '',
        'extra_json' => '{}',
    );

}
