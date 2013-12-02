<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/16/13
 * Time: 12:59 PM
 * Something meaningful about this file
 *
 */

class File
{
	const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

	protected static $data = array();

	static public $class_name = 'Model_file';

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		return $obj;
	}

	static public function get_by_id($_id)
	{
		$ofile = new Model_file();
		$result = $ofile->get_by_id($_id);
		return $result;
	}

	static public function get_by_object_id($object_id)
	{
		$ofile = new Model_file();
		$result = $ofile->get_by_object_id($object_id);
		return $result;
	}

	static public function get_empty_row()
	{
		$ofile = new Model_file();
		return $ofile::$_columns;
		$row = array();
		foreach ($ofile::$_columns as $column=>$type)
		{
			$row[] = $column;
		}
		return $row;
	}

	static public function get_author($_id)
	{
		return self::$sample_accounts[$_id];
	}

	static public function update(&$data, &$error)
	{
		if ( empty($data['_id']) )
		{
			$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		}
		$file = new Model_file();
		$result = $file->save($data, $error);
		return $result;
	}

	static public function reset($data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$file = new Model_file();

		if (! $exists = self::profile($data['_id']))
		{
			$error = array(
				'error' =>  255,
				'message' => __('Account does not exist'),
			);
			return FALSE;
		}
		else
		{
			//Add hash to account
			$exists['hash'] = md5('123mudar');
			$file->save($exists, $error);
			return TRUE;
		}
	}

	static public function filter($filter=array(), $sort=array(), $limit=array())
	{
		$ofile = new Model_file();
		$result = $ofile->filter($filter, $sort, $limit);

		return $result;
	}

	static public function delete_by_object_id($object_id, &$error)
	{
		$ofile = new Model_file();
		$result = $ofile->delete_by_object_id($object_id, $error);

		return $result;
	}

}
