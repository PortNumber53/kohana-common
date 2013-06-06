<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/18/13
 * Time: 3:50 PM
 *
 */

class Settings
{
	protected static $data = array();

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		//$obj::$template_file = $template_file_name;
		//echo $obj::$template_file;

		//if (empty(self::$template_file))
		//{
		//	self::$template_file = 'frontend';
		//}/
		//$this->template = 'template/' . $this->template_name . '/' . $this->template_file;

		return $obj;
	}

	static function is_logged()
	{
		$logged_in = Cookie::get('setting');
		return ! empty($logged_in);
	}

	static public function get($account_id = 0)
	{
		$settings = new Model_Settings();
		$data = $settings->get_by_account_id($account_id);

		return $data;
	}

	static public function get_by_id($_id = '')
	{
		$settings = new Model_Settings();
		$data = $settings->get_by_id($_id);

		return $data;
	}

	static public function update(&$data, &$error)
	{
		$settings = new Model_Settings();

		//Update settings
		return $settings->save($data, $error);
	}
}