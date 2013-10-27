<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 11:16 PM
 *
 */

class Model_Account extends Kohana_Model_Database
{
	private static $_table_name = 'account';
	private static $_primary_key = '_id';

	private static $_columns = array(
		'_id'           => '',
		'username'      => '',
		'password'      => '',
		'email'         => '',
		'name'          => '',
		'hash'          => '',
		'created_at'    => '',
		'modified_at'   => '',
		'extra_json'    => '',
		'pk_id'         => '',
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

	public function save(&$data, &$error)
	{
		$exists = $this->get_by_id($data['_id']);
		if ($exists)
		{
			$data['password'] = $exists['password'];
		}
		$data = array_intersect_key($data, self::$_columns);

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