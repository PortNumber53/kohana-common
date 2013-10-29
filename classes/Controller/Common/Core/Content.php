<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mauricio
 * Date: 5/14/13
 * Time: 12:47 PM
 * To change this template use File | Settings | File Templates.
 */

class Controller_Common_Core_Content extends Controller_Common_Core_Website
{
	public function action_browse()
	{
		$main = 'content/browse';

		View::bind_global('main', $main);
	}

	public function action_view()
	{
		$request = $this->request->param('request');
		$type = $this->request->param('type');
		$full_request = ($request === '/') ? $request : "$request.$type";

		$data = Content::get($full_request);
		if ( ! $data)
		{
			throw HTTP_Exception::factory(404, 'Document not found!');
		}
		View::bind_global('data', $data);

		$main = View::factory('content/main')->render();
		View::bind_global('main', $main);
	}
}