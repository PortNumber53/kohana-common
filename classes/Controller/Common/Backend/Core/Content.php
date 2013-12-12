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
		View::bind_global('main', $main);

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
		$content_data = Content::get_by_id($full_request);
		View::bind_global('content_data', $content_data);
	}

	public function action_ajax_edit()
	{
		$this->output = array(
			'posted' => $_POST,
		);

		$content_data = array(
			'_id'          => $_POST['id'],
			'object_id'    => $_POST['object_id'],
			'title'        => $_POST['title'],
			'mimetype'     => $_POST['mimetype'],
			'url'          => $_POST['url'],
		);
		if (empty($_POST['id']))
		{
			$content_data['_id'] = str_replace('//', '/', '/' . DOMAINNAME . '/' . $_POST['url']);
		}
		if (isset($_POST['body']))
		{
			$content_data['body'] = htmlentities($_POST['body']);
		}
		if (isset($_POST['section_title']))
		{
			for ($loop = 0; $loop < count($_POST['section_title']); $loop++)
			{
				$content_data['sections'][$loop] = array(
					'title' => $_POST['section_title'][$loop],
					'content' => $_POST['section_content'][$loop],
				);
			}
		}
		/*
		if (isset($_POST['section_content']))
		{
			foreach ($_POST['section_content'] as $section)
			{
				$content_data['sections'][]['content'] = $section;
			}
		}
		*/
		$error = FALSE;
		if ( ! empty($content_data['sections']))
		{
			$this->output['sections'] = $content_data['sections'];
		}

		$result = Content::update($content_data, $error);
		$this->output['error'] = $error;
		$this->output['result'] = $result;
	}

}