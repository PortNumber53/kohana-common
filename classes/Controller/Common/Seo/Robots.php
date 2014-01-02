<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 12/27/13
 * Time: 1:06 AM
 * Something meaningful about this file
 *
 */

class Controller_Common_Seo_Robots extends Controller_Website
{
	public function action_index()
	{
		$this->auto_render = FALSE;
		$config = Kohana::$config->load('seo.robots');
		$view_html = '';
		foreach ($config as $line)
		{
			$view_html .= "$line\n";
		}

		$this->response->headers('Content-type', 'text/plain');
		$this->response->headers('Content-length', strlen($view_html));
		$this->response->body( $view_html );

	}
}