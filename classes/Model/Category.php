<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 5:51 PM
 * Something meaningful about this file
 *
 */

class Model_Category extends Model_Abstract
{
	public static $_table_name = 'category';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'object_id'     => '',
		'name'          => '',
		'description'   => '',
		'status'        => '',
		'created_at'    => '',
		'modified_at'   => '',
		'extra_json'    => '',
	);

}
