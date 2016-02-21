<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Gallery
 */
class Model_Gallery extends Model_Abstract
{
    public static $_table_name = 'gallery';
    public static $_primary_key = 'galleryid';

    public static $_columns = array(
        'galleryid' => 0,
        '_id' => '',
        'url' => '',
        'category_id' => 0,
        'status' => '',
        'name' => '',
        'description' => '',
        'created_at' => '',
        'updated_at' => '',
        'extra_json' => '{}',
    );

    public static $_json_columns = array(
        'tags' => '',
    );

    public static function _before_save(&$data = array())
    {
        parent::_before_save($data);
        if (empty($data['object_id'])) {
            $data['object_id'] = Model_Sequence::nextval();
        }
        if (!empty($data['object_id']) && !empty($data['name'])) {
            $data['url'] = '/' . $data['object_id'] . '-' . URL::title($data['name']) . '/';
        }

    }

}
