<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Sequence
 */
class Model_Sequence extends Model_Abstract
{
    public static $_table_name = 'sequence';
    public static $_primary_key = '_id';

    public static $_columns = array(
        '_id' => '',
    );

    static private $prefix = 'seq_';

    public static function nextval()
    {
        $config = Kohana::$config->load('database')->as_array();
        $database = (string)Database::instance();
        $settings = Arr::path($config, $database);
        $settings_string = strtolower(json_encode($settings));

        if (strpos($settings_string, 'mysql') !== FALSE) {
            $result = DB::insert('autoincrement', array_keys(array()))->values(array())->execute();
            $result = $result[0];
        }
        if ((strpos($settings_string, 'pgsql') !== FALSE) || (strpos($settings_string, 'postgresql') !== FALSE)) {
            $query = DB::select(DB::expr('nextval(\'object\') as sequence'));
            $result = $query->execute()->get('sequence');
        }
        return $result;
    }

}
