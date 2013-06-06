<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/16/13
 * Time: 2:20 AM
 *
 */

class Model_Sequence extends Kohana_Model_Database
{
	private static $_table_name = 'sequence';
	private static $_primary_key = '_id';

	private static $_columns = array(
		'_id' => '',
		'username' => '',
		'password' => '',
		'name' => '',
		'email' => '',
		'date_of_birth' => '',
		'gender' => '',
	);

	static private $prefix = 'seq_';

	static public function nextval()
	{
		$query = DB::select(DB::expr('nextval(\'object\') as sequence'));
		//echo (string) $query;
		$result = $query->execute()->get('sequence');
		return $result;
	}


	public function get_by_id($_id)
	{
		$query = DB::select()->from(self::$_table_name)->where(self::$_primary_key, '=', $_id);
		//echo (string) $query;
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

	public function save(&$data, &$error)
	{
		$data = array_intersect_key($data, self::$_columns);

		$exists = $this->get_by_id($data['_id']);
		ksort($data);
		$row_data = array(
			'_id' => $data['_id'],
			'data' => json_encode($data),
		);
		try
		{
			if ($exists)
			{
				//Update
				//echo "UPDATE<br>\n";
				$return = DB::update(self::$_table_name)->set($row_data)->where(self::$_primary_key, '=', $row_data[self::$_primary_key])->execute();
			}
			else
			{
				//Insert
				//echo "INSERT<br>\n";
				$result = DB::insert(self::$_table_name, array_keys($row_data))->values($row_data)->execute();
			}
		}
		catch (Exception $e)
		{
			$error = array(
				'error' => $e->getCode(),
				'message' => $e->getMessage(),
			);
			//var_dump($error);die();
			return FALSE;
		}
		return TRUE;
	}


}