<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/16/13
 * Time: 2:20 AM
 *
 */

class Model_Sequence extends Model_Abstract
{
	public static $_table_name = 'sequence';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id' => '',
	);

	static private $prefix = 'seq_';

	static public function nextval()
	{
		$config = Kohana::$config->load('database')->as_array();
		$database = (string) Database::instance();
		$settings = Arr::path($config, $database);
		$settings_string= strtolower(json_encode($settings));

		if (strpos($settings_string, 'mysql') !== FALSE)
		{
			$result = DB::insert('autoincrement', array_keys(array()))->values(array())->execute();
		}
		if ((strpos($settings_string, 'pgsql') !== FALSE) || (strpos($settings_string, 'postgresql') !== FALSE))
		{
			$query = DB::select(DB::expr('nextval(\'object\') as sequence'));
			$result = $query->execute()->get('sequence');
		}
		return $result[0];
	}

}
