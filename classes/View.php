<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 2:44 AM
 *
 */

class View extends Kohana_View
{
	static public $buffers = array();

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