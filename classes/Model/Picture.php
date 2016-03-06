<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Picture
 */
class Model_Picture extends Model_Abstract
{
    protected static $_table_name = 'picture';
    protected static $_primary_key = 'pictureid';
    protected static $_parent_key = 'productid';
    protected static $_columns = array(
        'pictureid' => 0,
        'productid' => 0,
        'filesize' => 0,
        'sequence' => 9999,
        'folder' => '',
        'image_filepath' => '',
        'thumb_filepath' => '',
        'md5_hash' => '',
    );
    protected static $_json_columns = array();

}
