<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 6:01 PM
 * Something meaningful about this file
 *
 */

class Model_Product extends Model_Abstract
{
	public static $_table_name = 'product';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'object_id'     => 0,
		'category_id'   => 0,
		'status'        => '',
		'name'          => '',
		'description'   => '',
		'created_at'    => '',
		'modified_at'   => '',
		'extra_json'    => '{}',
	);

	public static $_json_columns = array(
		'tags'			=> '',
	);

}
