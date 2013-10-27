<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 5:34 PM
 * Something meaningful about this file
 *
 */

class Core_Masher
{
	protected $css_list = array();
	protected $js_list = array();
	public $identifier_css = '';
	public $identifier_js = '';
	protected $mashed_css = array();
	protected $mashed_js = array();

	/**
	 * @var  string  default instance name
	 */
	public static $default = 'default';

	/**
	 * @var  array  Masher instances
	 */
	public static $instances = array();

	public static function instance($name = NULL, array $config = NULL)
	{
		if ($name === NULL)
		{
			// Use the default instance name
			$name = Masher::$default;
		}

		if ( ! isset(Masher::$instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the configuration for this database
				$config = Kohana::$config->load('masher.' . Website::$environment[Kohana::$environment]);
			}

			// Store the masher instance
			Masher::$instances[$name] = new Masher($name, $config);
		}

		return Masher::$instances[$name];
	}

	/**
	 * Stores the masher configuration locally and name the instance.
	 *
	 * [!!] This method cannot be accessed directly, you must use [Masher::instance].
	 *
	 * @return  void
	 */
	public function __construct($name, array $config)
	{
		// Set the instance name
		$this->_instance = $name;

		$this->_config = $config;
	}

	public function add_js($file, $inline = FALSE)
	{
		if (isset($this->_config[$file]))
		{
			$file = $this->_config[$file];
		}
		$this->js_list[] = array(
			'inline' => $inline,
			'flat'   => $file,
		);
	}

	public function add_css($file, $inline = FALSE)
	{
		if (isset($this->_config[$file]))
		{
			$file = $this->_config[$file];
		}
		$this->css_list[] = array(
			'inline' => $inline,
			'flat'   => $file,
		);
	}

	public function mark_css()
	{
		$mark = uniqid('css-' . $this->_instance, TRUE);
		$this->identifier_css = $mark;
		return $mark;
	}

	public function mark_js()
	{
		$mark = uniqid('js-' . $this->_instance, TRUE);
		$this->identifier_js = $mark;
		return $mark;
	}


	public function render_css()
	{
		$return = '';
		foreach ($this->css_list as $key=>$css)
		{
			if ($css['inline'] === TRUE)
			{
				$return .= "<style type=\"text/css\">\n";
				$return .= $css['flat'];
				$return .= "</style>\n";

			}
			else
			{
				$return .= "<link href=\"" . $css['flat'] . "\" rel=\"stylesheet\">";
			}
		}
		return $return;
	}

	public function render_js()
	{
		$return = '';
		foreach ($this->js_list as $key=>$script)
		{
			if ($script['inline'] === TRUE)
			{
				$return .= "<script type=\"text/css\">\n";
				$return .= $script['flat'];
				$return .= "</script>\n";

			}
			else
			{
				$return .= "<script src=\"" . $script['flat'] . "\"></script>\n";
			}
		}
		return $return;
	}

}