<?php
/**
 * Date: 6/6/13
 * Time: 1:14 PM
 * Something meaninful about this file
 */

class Website
{
	public static $default = 'default';


	static public $template_name = '';
	static public $template_file = '';
	protected static $settings = array();

	static function load_settings()
	{
		//echo "CONSTRUCTOR<br />";
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
		//echo "SET TO $name<br>";
		self::$template_name = $name;
	}
	static public function set_file($name)
	{
		//echo "SET FILE $name<br>";
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

		//if TEMPLATE?
		//'template.' . self::$template_file . '.' . self::$template_name . '.' .

		//echo "GET: template." . self::$template_file . '.' . self::$template_name . '.' . $path . "<br>";
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

		//if TEMPLATE?
		//

		//echo 'GET: template.' . self::$template_file . '.' . self::$template_name . '.' .$path . "<br>";
		return Arr::path(self::$settings, 'template.' . self::$template_file . '.' . self::$template_name . '.' .$path, $default);
	}


}