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
		View::set_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$content_array = Content::filter($filter);
		View::set_global('content_array', $content_array);
	}

	public function action_view()
	{
		$main = 'content/main';
		View::bind_global('main', $main);

		$request = $this->request->param('request');
		$type = $this->request->param('type');
		$full_request = ($request === '/') ? $request : "$request.$type";

		//Check for static content
		if (Kohana::find_file('views', $full_request))
		{
			$main = $full_request;
		}
		else
		{
			$data = Content::get($full_request);
			if ( ! $data)
			{
				throw HTTP_Exception::factory(404, 'Document not found!');
			}
			View::bind_global('data', $data);
		}
	}

}
