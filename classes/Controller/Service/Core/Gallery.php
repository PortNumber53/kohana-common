<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/16/13
 * Time: 1:20 PM
 * Something meaningful about this file
 *
 */

class Controller_Service_Core_Gallery extends Controller_Service_Core_Service
{

	public function action_ajax_delete()
	{
		$error = FALSE;
		$this->output = array(
			'posted' => $_POST,
		);

		$result = Gallery::delete_by_object_id( (int) $_POST['what'], $error);
		if ($result)
		{
			$this->output['redirect_url'] = $_POST['back_url'];
		}

		$this->output['result'] = $result;
		$this->output['error'] = $error;

		$filter = array();
		$gallery_array = Gallery::filter($filter);
		View::set_global('gallery_array', $gallery_array);
		$this->output['table_body'] = View::factory('gallery/ajax_browse')->render();
	}

}