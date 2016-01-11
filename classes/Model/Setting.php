<?php defined('SYSPATH') or die('No direct script access.');

class Model_Setting extends Model_Abstract
{
	public static $_table_name = 'setting';
	public static $_primary_key = 'settingid';

	public static $_columns = array(
		'settingid' => 0,
		'name' => '',
		'extra_json' => '',
		'created_at' => '',
		'updated_at' => '',
	);

	public static $_json_columns = array();


	public static function getDataByName($name)
	{
		$cache_key = '/' . static::$_table_name . ':by_name:' . $name;
		$row = Cache::instance('redis')->get($cache_key);
		if (true || empty($row)) {
			$query = DB::select()->from(static::$_table_name)->where('name', '=', $name);
			$row = $query->execute()->as_array();
			if (count($row) == 1) {
				$row = array_shift($row);
				$data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), true);
				unset($data['_id']);
				$row = array_merge($row, $data);
				unset($row['data']);
				$extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row, 'extra_json',
					'{}'), true);
				unset($extra_json['_id']);
				$row = array_merge($row, $extra_json);
				unset($row['extra_json']);

				Cache::instance('redis')->set($cache_key, json_encode($row));
			}
		} else {
			$row = json_decode($row, true);
		}

		// Data check stuff?
		return $row;
	}
}
