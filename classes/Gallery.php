<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/16/13
 * Time: 1:09 PM
 * Something meaningful about this gallery
 *
 */

class Gallery extends Abstracted
{
	const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

	protected static $data = array();

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		return $obj;
	}

	static public function get_empty_row()
	{
		$ogallery = new Model_Gallery();
		return $ogallery::$_columns;
		$row = array();
		foreach ($ogallery::$_columns as $column=>$type)
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
		$gallery = new Model_Gallery();
		$result = $gallery->save($data, $error);
		return $result;
	}

	static public function reset($data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$gallery = new Model_Gallery();

		if (! $exists = self::progallery($data['_id']))
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
			$gallery->save($exists, $error);
			return TRUE;
		}
	}

	static public function delete_by_object_id($object_id, &$error)
	{
		$ogallery = new Model_Gallery();
		$result = $ogallery->delete_by_object_id($object_id, $error);

		return $result;
	}

}
