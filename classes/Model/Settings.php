<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Settings
 */
class Model_Settings extends Model_Abstract
{
    private static $_table_name = 'settings';
    private static $_primary_key = '_id';

    private static $_columns = array(
        '_id' => 0,
        'account_id' => 0,
        'data' => '',
        'key' => '',
        'value' => '',
    );

    public function get_by_account_id($_id)
    {
        $query = DB::select()->from(self::$_table_name)->where('account_id', '=', $_id);
        $result_set = $query->execute()->as_array();
        if (count($result_set) == 1) {
            $result = array_shift($result_set);
            $array = json_decode($result_set[0]['data'], TRUE);
            unset($result['data']);
            $result = array_merge($result, $array);
            return $result;
        } else {
            return false;
        }
    }

    public function _before_save(&$data = array())
    {
        // TODO: Implement _before_save() method.
    }
}
