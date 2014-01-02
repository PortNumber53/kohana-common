<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 12/23/13
 * Time: 1:22 PM
 * Something meaningful about this file
 *
 */

class Model_SEO extends Model_Abstract
{
	public static $_table_name = 'seo';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'object_id'     => '',
		'extra_json'    => '',
		'created_at'    => '',
		'modified_at'   => '',
	);

	public static $_json_columns = array(
	);

}