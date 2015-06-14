<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 10/27/13
 * Time: 5:37 PM
 * Something meaningful about this file
 *
 */
abstract class Model_Core_Abstract extends Model_Database
{
    protected static $_table_name = 'CUSTOMIZE_TABLE_NAME';
    protected static $_primary_key = 'CUSTOMIZE_PRIMARY_KEY_NAME';

    protected static $_columns = array(
        'CUSTOMIZE_COLUMN_NAMES' => 'YOU_CAN_LEAVE_THIS_EMPTY',
    );

    protected static $_json_columns = array();

    abstract public function get_by_id($_id, &$options = array());

    public static function getDataById($id)
    {
        return static::_getDataById($id);
    }

    public static function _getDataById($id)
    {
        return false;
    }

    abstract public function get_by_object_id($object_id, &$options = array());

    public static function getDataByParentId($parentId, $limit=0, $offset=0)
    {
        return static::_getDataByParentId($parentId, $limit, $offset);
    }

    public static function _getDataByParentId($parentId, $limit, $offset)
    {
        return array(
            'rows' => array(),
            'count' => 0,
            'limit' => 0,
            'offset' => 0,
            'pages' => 1,
        );
    }

    abstract public function _before_save(&$data = array());

    abstract public function save(&$data, &$error, &$options = array());

    abstract public function filter($filter = array(), $sort = array(), $limit = array());
}
