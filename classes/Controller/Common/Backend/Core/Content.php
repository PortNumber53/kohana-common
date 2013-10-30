<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:35 AM
 *
 */

class Controller_Common_Backend_Core_Content extends Controller_Common_Core_Website
{

	public function action_edit()
	{
		$main = 'content/backend/edit';
		View::set_global('main', $main);

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
		$content_data = Content::get($full_request);
		View::bind_global('content_data', $content_data);
	}

	public function action_ajax_edit()
	{
		$this->output = array(
			'posted' => $_POST,
		);

		$content_data = array(
			'_id'      => str_replace('//', '/', '/' . DOMAINNAME . '/' . URL::title($_POST['url'])),
			'title'    => $_POST['title'],
			'body'     => $_POST['body'],
			'mimetype' => $_POST['mimetype'],
			'url'      => $_POST['url'],
		);
		$error = FALSE;

		$result = Content::update($content_data, $error);
		$this->output['error'] = $error;
		$this->output['result'] = $result;
	}

}