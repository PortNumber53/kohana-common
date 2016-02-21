<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Image
 */
class Model_Image extends Model_Abstract
{
    protected static $_table_name = 'image';
    protected static $_primary_key = '_id';

    protected static $_columns = array(
        '_id' => '',
        'url' => '',
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
