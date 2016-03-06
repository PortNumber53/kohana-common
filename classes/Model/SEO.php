<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_SEO
 */
class Model_SEO extends Model_Abstract
{
    protected static $_table_name = 'seo';
    protected static $_primary_key = '_id';

    protected static $_columns = array(
        '_id' => '',
        'object_id' => '',
        'extra_json' => '',
        'created_at' => '',
        'modified_at' => '',
    );

    protected static $_json_columns = array();

}
