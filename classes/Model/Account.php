<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 11:16 PM
 *
 */

class Model_Account extends Model_Abstract
{
	public static $_table_name = 'account';
	public static $_primary_key = '_id';

	public static $_columns = array(
		'_id'           => '',
		'object_id'     => '',
		'profile'       => '',
		'username'      => '',
		'password'      => '',
		'name'          => '',
		'avatar'        => 'picture',
		'email'         => '',
		'hash'          => '',
		'created_at'    => '',
		'modified_at'   => '',
		'extra_json'    => '',
	);

	public static $_json_columns = array(
	);

	public function _before_save(&$data = array())
	{
		unset($data['password1'], $data['password2'], $data['remember_me']);
		parent::_before_save($data);
	}

}