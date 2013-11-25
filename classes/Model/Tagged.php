<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 9:58 PM
 * Something meaningful about this file
 *
 */

class Model_Tagged extends Model_Abstract
{
	public static $_table_name = 'tagged';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'object_id'     => 0,
		'associated_id' => 0,
		'created_at'    => '',
		'modified_at'   => '',
		'extra_json'    => '{}',
	);

}
