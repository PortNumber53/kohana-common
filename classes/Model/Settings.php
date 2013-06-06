<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/18/13
 * Time: 4:50 PM
 *
 */

class Model_Settings extends Kohana_Model_Database
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

	public function get_by_id($_id)
	{
		$query = DB::select()->from(self::$_table_name)->where(self::$_primary_key, '=', $_id);
		//echo (string) $query;
		$result_set = $query->execute()->as_array();
		if (count($result_set) == 1)
		{
			$result = $result_set[0];
			//var_dump($result_set[0]['data']);
			$array = json_decode($result_set[0]['data'], TRUE);
			unset($result['data']);
			$result = array_merge($result, $array);

			return $result;
		}
		else
		{
			return false;
		}
	}

	public function get_by_account_id($_id)
	{
		$query = DB::select()->from(self::$_table_name)->where('account_id', '=', $_id);
		//echo (string) $query;
		$result_set = $query->execute()->as_array();
		if (count($result_set) == 1)
		{
			$result = $result_set[0];
			//var_dump($result_set[0]['data']);
			$array = json_decode($result_set[0]['data'], TRUE);
			unset($result['data']);
			$result = array_merge($result, $array);
			return $result;
			/*
			foreach ($result_set as $key=>&$value)
			{
				$array = json_decode($value['data'], TRUE);
				unset($value['data']);
				$result_set[$key] = array_merge($result_set[$key], $array);
			}
			return $result_set;
			*/
		}
		else
		{
			return false;
		}
	}

	public function save(&$data, &$error)
	{
		$exists = $this->get_by_id($data['_id']);
		$data = array_intersect_key($data, self::$_columns);

		ksort($data);
		$row_data = array(
			'_id' => $data['_id'],
			'account_id' => $data['account_id'],
			'data' => json_encode($data),
		);
		try
		{
			if ($exists)
			{
				//Update
				///echo "UPDATE<br>\n";
				$return = DB::update(self::$_table_name)->set($row_data)->where(self::$_primary_key, '=', $row_data[self::$_primary_key])->execute();
			}
			else
			{
				//Insert
				//echo "INSERT<br>\n";
				$row_data['pk_id'] = Model_Sequence::nextval();
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