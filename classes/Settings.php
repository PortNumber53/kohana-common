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
		$website_settings = parse_ini_file(APPPATH . '..' . DIRECTORY_SEPARATOR . '.settings', TRUE);
		//define('WEBSITE_SETTINGS', json_encode($website_settings));

		$obj = new self();
		$obj::$data = $website_settings;

		return $obj;
	}

	public static function value($array_path)
	{
		$value = Arr::path(self::$data, $array_path, NULL);
		if (empty($value))
		{
			throw new Kohana_Exception ('Parameter not found: ' . $array_path);
		}
		return $value;
	}

	public static function get($account_id = 0)
	{
		$settings = new Model_Settings();
		$data = $settings->get_by_account_id($account_id);

		return $data;
	}

	public static function get_by_id($_id = '')
	{
		$settings = new Model_Settings();
		$data = $settings->get_by_id($_id);

		return $data;
	}

	public static function update(&$data, &$error)
	{
		$settings = new Model_Settings();

		//Update settings
		return $settings->save($data, $error);
	}

    public static function enabledFeature($featureId)
    {
        return false;
    }
}
