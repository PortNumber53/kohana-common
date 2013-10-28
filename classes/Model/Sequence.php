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

	public function save(&$data, &$error, &$options=array())
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