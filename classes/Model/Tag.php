<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Tag
 */
class Model_Tag extends Model_Abstract
{
    protected static $_table_name = 'tag';
    protected static $_primary_key = 'tagid';

    protected static $_columns = array(
        'tagid' => '',
        'galleryid' => 0,
        'tag' => '',
        'created_at' => '',
        'updated_at' => '',
        'extra_json' => '{}',
    );

}
