<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/24/13
 * Time: 10:11 PM
 * Something meaningful about this file
 *
 */

class Model_Image extends Model_Abstract
{
	public static $_table_name = 'image';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'url'           => '',
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

	public function _before_save(&$data = array())
	{
		parent::_before_save($data);
		if (empty($data['object_id']))
		{
			$data['object_id'] = Model_Sequence::nextval();
		}
		//unset($data['password1'], $data['password2'], $data['remember_me']);
		if ( ! empty($data['object_id']) && ! empty($data['name']))
		{
			$data['url'] = '/' . $data['object_id'] . '-' . URL::title($data['name']) . '/';
		}

	}
}
