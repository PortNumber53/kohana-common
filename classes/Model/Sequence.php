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
		//PostgreSQL
		//$query = DB::select(DB::expr('nextval(\'object\') as sequence'));
		//echo (string) $query;
		//$result = $query->execute()->get('sequence');
		//return $result;

		//MySQL
		$result = DB::insert('autoincrement', array_keys(array()))->values(array())->execute();
		return $result[0];
	}

}
