<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 2:44 AM
 *
 */

class View extends Kohana_View
{
	static public $template_name = '';

	static public $buffers = array();

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  view filename
	 *
	 * @return  View
	 * @throws  View_Exception
	 */
	public function set_filename($file)
	{
		if (($path = Kohana::find_file('views', $file)) === FALSE)
		{
			if (($path = Kohana::find_file('views', 'template/' . self::$template_name . '/' . $file)) === FALSE)
			{
				if (($path = Kohana::find_file('views', 'template/' . self::$template_name . '/modules/' . $file)) === FALSE)
				{
					if (($path = Kohana::find_file('views', 'template/default/' . $file)) === FALSE)
					{
						if (($path = Kohana::find_file('views', 'template/default/modules/' . $file)) === FALSE)
						{
							throw new View_Exception('The requested view :file could not be found (template :template)', array(
								':file' => $file,
								':template' => self::$template_name,
							));
						}
					}
				}
			}
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}

	public static function start_buffer($buffer_name, $html_piece='</body>')
	{
		self::$buffers[$buffer_name] = array(
			'content' => '',
			'html_piece' => $html_piece,
		);
		ob_start();
	}

	public static function end_buffer($buffer_name)
	{
		self::$buffers[$buffer_name]['content'] = ob_get_clean();
	}

	public function render($file = NULL)
	{
		$rendered = parent::render($file);

		foreach (self::$buffers as $key=>$buffer)
		{
			if ( ! empty($buffer['content']) && ! empty($buffer['html_piece']))
			{
				$rendered = str_replace($buffer['html_piece'], $buffer['content'] . $buffer['html_piece'], $rendered);
			}
		}
		return $rendered;
	}

}