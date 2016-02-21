<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Tag
 */
class Model_Tag extends Model_Abstract
{
    public static $_table_name = 'tag';
    public static $_primary_key = 'tagid';

    public static $_columns = array(
        'tagid' => '',
        'galleryid' => 0,
        'tag' => '',
        'created_at' => '',
        'updated_at' => '',
        'extra_json' => '{}',
    );

}
