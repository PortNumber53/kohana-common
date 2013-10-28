<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:26 AM
 *
 */

class Model_Content extends Model_Abstract
{
	public static $_table_name = 'content';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'          => '',
		'author_id'    => '',
		'url'          => '',
		'mimetype'     => '',
		'title'        => '',
		'body'         => '',
		'created_at'   => '',
		'modified_at'  => '',
		'extra_json'   => '',
		'pk_id'        => '',
	);

	public function get_by_account_id($account_id)
	{
		$query = DB::select()->from(self::$_table_name)->where('account_id', '=', $account_id);
		echo (string) $query;
		$result_set = $query->execute()->as_array();
		if (count($result_set) > 0)
		{
			foreach ($result_set as $key=>&$item)
			{
				//$result = $result_set[$key];
				$array = json_decode($result_set[$key]['data'], true);
				unset($result_set[$key]['data']);
				$result_set[$key] = array_merge($result_set[$key], $array);
			}

			return $result_set;
		}
		else
		{
			return false;
		}
	}


}