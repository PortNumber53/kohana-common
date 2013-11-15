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

		$data = array(
			'message' => $this->getMessage(),
		);
		View::bind_global('data', $data);

		$request = Request::factory('http/404.html');
		$response = $request->execute();

		$response->status(404);
		return $response;
	}

}
