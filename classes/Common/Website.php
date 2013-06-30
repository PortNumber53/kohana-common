<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 6/15/13
 * Time: 1:20 AM
 * Something meaningful about this file
 *
 */

class Common_Website
{
	public static $default = 'default';

	static public $template_name = '';
	static public $template_file = '';
	protected static $settings = array();

	static function load_settings()
	{
		self::$settings = Kohana::$config->load('website')->as_array();
	}

	public static function factory()
	{
		$obj = new self();
		$obj::$settings = Kohana::$config->load('website')->as_array();

		//$obj::$template_file = $template_file_name;
		//echo $obj::$template_file;

		if (empty(self::$template_file))
		{
			self::$template_file = 'frontend';
		}
		//$this->template = 'template/' . $this->template_name . '/' . $this->template_file;

		return $obj;
	}

	static public function set_template($name)
	{
		self::$template_name = $name;
	}
	static public function set_file($name)
	{
		self::$template_file = $name;
	}

	static public function get($path, $default=NULL)
	{
		if (empty(self::$template_file))
		{
			//echo 'Empty template_file; ';
			//self::set_template();
		}
		if (empty(self::$settings))
		{
			//echo 'load settings ';
			self::load_settings();
		}

		return Arr::path(self::$settings, $path, $default);
	}

	static public function template($path, $default=NULL)
	{
		if (empty(self::$template_file))
		{
			//echo 'Empty Template_file?<br> ';
			//self::set_template();
		}
		if (empty(self::$settings))
		{
			//echo 'load settings ';
			self::load_settings();
		}

		return Arr::path(self::$settings, 'template.' . self::$template_file . '.' . self::$template_name . '.' .$path, $default);
	}

	static public function domain()
	{
		return str_replace('www.', '', $_SERVER['SERVER_NAME']);
	}


	const NOT_CACHED = '¿NOT_CACHED?';
	static $data = array();

	static public function service($array_path, $function, $params=array(), $default=NULL)
	{
		//Check if data is "cached"
		$data = Arr::path(self::$data, $array_path, self::NOT_CACHED);
		if ($data === self::NOT_CACHED)
		{
			//echo "$array_path IS NOT CACHED<br>";
			//Get the data
			list($class, $method) = explode('/', $function);
			$data = $class::$method($params);
			//var_dump($data);die();

			$return = '123';
			if ( ! $return = Arr::path(self::$data, $array_path, $default))
			{
				$path = explode('.', $array_path);

				$root = &self::$data;
				foreach ($path as $step)
				{
					if (isset($root[$step]))
					{
						//$root[$step] = '123';
					}
					else
					{
						$root[$step] = array($data);
						$root = &$root[$step];
					}
				}
			}
		}
		else
		{
			//echo "$array_path is CACHED<br>";
			$data = $data[0];
			//var_dump($data);die();
		}
		return $data;
	}

}