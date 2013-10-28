<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 5:54 PM
 * Something meaningful about this file
 *
 */

abstract class Core_Abstract
{

	abstract public function get_by_id($_id, &$options=array());

	abstract public function get_by_object_id($object_id, &$options=array());

}