<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 3:42 PM
 * Something meaningful about this file
 *
 */

class Model_Abstract extends Model_Database
{
	private static $_table_name = 'content';
	private static $_primary_key = '_id';

	private static $_columns = array(
		'_id' => '',
	);


	public function get_by_id($_id)
	{
		$query = DB::select()->from(self::$_table_name)->where(self::$_primary_key, '=', $_id);
		#echo (string) $query;
		$result_set = $query->execute()->as_array();
		if (count($result_set) == 1)
		{
			$result = $result_set[0];
			$array = json_decode($result_set[0]['data'], true);
			unset($result['data']);
			$result = array_merge($result, $array);

			return $result;
		}
		else
		{
			return false;
		}
	}


}