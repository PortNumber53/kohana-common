<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:35 AM
 *
 */

class Controller_Content_Backend_Base_Content extends Controller_Common_Base_Website
{

	public function action_edit()
	{
		$request = $this->request->param('request');
		$type = $this->request->param('type');
		$full_request = ($request === '/') ? $request : "$request.$type";

		if ($post = $this->request->post())
		{
			$error = array();
			if ($result = Content::update($post, $error))
			{

			}
		}
		$data = Content::get($full_request);

		View::bind_global('data', $data);
		$main = View::factory('content/backend/edit')->render();

		View::set_global('main', $main);
	}

}