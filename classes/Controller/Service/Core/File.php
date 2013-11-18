<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/16/13
 * Time: 1:21 PM
 * Something meaningful about this file
 *
 */

class Controller_Service_Core_File extends Controller_Service_Core_Service
{

	public function action_ajax_delete()
	{
		$error = FALSE;
		$this->output = array(
			'posted' => $_POST,
		);

		$result = File::delete_by_object_id($_POST['what'], $error);
		if ($result)
		{
			$this->output['redirect_url'] = $_POST['back_url'];
		}

		$this->output['result'] = $result;
		$this->output['error'] = $error;

		$filter = array();
		$file_array = File::filter($filter);
		View::set_global('file_array', $file_array);
		$this->output['table_body'] = View::factory('file/ajax_browse')->render();
	}
}