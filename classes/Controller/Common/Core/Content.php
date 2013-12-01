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
		$page = (int) Arr::path($_GET, 'page', 1);
		$main = 'content/browse';
		View::bind_global('main', $main);

		$limit = 15;
		$offset = ($page - 1);
		$sort = array();
		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$filtered_content = Content::filter($filter, $sort, $limit, $offset);

		View::bind_global('filtered_content', $filtered_content);
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
			$data = Content::get_by_id($full_request);
			if ( ! $data)
			{
				throw HTTP_Exception::factory(404, 'Document not found!');
			}
			View::bind_global('data', $data);
		}
	}

}
