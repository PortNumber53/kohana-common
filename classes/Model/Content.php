<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:26 AM
 *
 */

class Model_Content extends Kohana_Model_Database
{
	private static $_table_name = 'content';
	private static $_primary_key = '_id';

	private static $_columns = array(
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

	public function get_by_id($_id)
	{
		$query = DB::select()->from(self::$_table_name)->where(self::$_primary_key, '=', $_id);
		echo (string) $query;
		$result_set = $query->execute()->as_array();
		if (count($result_set) == 1)
		{
			$result = $result_set[0];
			$array = json_decode($result_set[0]['extra_json'], true);
			unset($result['extra_json']);
			$result = array_merge($result, $array);

			return $result;
		}
		else
		{
			return false;
		}
	}

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

	public function save(&$data, &$error)
	{
		$data = array_intersect_key($data, self::$_columns);

		$exists = $this->get_by_id($data['_id']);
		if (empty($data['pk_id']))
		{
			$data['pk_id'] = Model_Sequence::nextval();
		}
		ksort($data);
		//echo'<pre>';var_dump($data);echo'</pre>';die();
		$row_data = array(
			'_id' => $data['_id'],
			'data' => json_encode($data),
			'pk_id' => $data['pk_id'],
		);
		try
		{
			if ($exists)
			{
				//Update
				$return = DB::update(self::$_table_name)->set($row_data)->where(self::$_primary_key, '=', $row_data[self::$_primary_key])->execute();
			}
			else
			{
				//Insert


				$result = DB::insert(self::$_table_name, array_keys($row_data))->values($row_data)->execute();
			}
		}
		catch (Exception $e)
		{
			$error = array(
				'error' => $e->getCode(),
				'message' => $e->getMessage(),
			);
			var_dump($error);die();
			return FALSE;
		}
		return TRUE;
	}

}