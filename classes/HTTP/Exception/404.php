<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 2:17 AM
 *
 */

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

	public function get_response()
	{
		$response = Response::factory();

		$view = View::factory('errors/http/404');

		// We're inside an instance of Exception here, all the normal stuff is available.
		$view->message = $this->getMessage();

		$response->body($view->render());

		return $response;
	}

}