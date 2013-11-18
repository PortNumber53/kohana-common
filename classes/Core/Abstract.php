<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 5:54 PM
 * Something meaningful about this file
 *
 */

interface iAbstractTable
{
	static public function get_by_id($_id, &$options=array());

	static public function get_by_object_id($object_id, &$options=array());

	static public function check_permission($data, &$option=array());
}

abstract class Core_Abstract implements iAbstractTable
{
	static public function get_by_id($_id, &$options=array())
	{
		$ogallery = new Model_Gallery();
		$result = $ogallery->get_by_id($_id);
		return $result;
	}

	static public function get_by_object_id($object_id, &$options=array())
	{
		$class = 'Model_'.get_called_class();
		$oTable = new $class;
		$result = $oTable->get_by_object_id($object_id);
		return $result;
	}

	static public function check_permission($data, &$option = array())
	{
		// TODO: Implement check_permission() method.

		//$owner_id  = $data['owner_id'];
		//$entity    = $data['entity'];
		//$object_id = $data['obtect_id'];
		return TRUE;
	}

}