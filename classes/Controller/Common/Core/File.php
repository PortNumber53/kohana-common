<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_File
 */
class Controller_Common_Core_File extends Controller_Website
{
	public function action_browse()
	{
		$main = 'file/browse';
		View::bind_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$file_array = File::filter($filter);
		View::bind_global('file_array', $file_array);
	}

	public function action_edit()
	{
		$object_id = $this->request->param('id');
		$file_data = File::get_by_object_id($object_id);
		if ( ! $file_data)
		{
			$file_data = File::getEmptyRow();
		}
		View::bind_global('file_data', $file_data);

		$main = 'file/edit';
		View::bind_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$file_array = File::filter($filter);
		View::bind_global('file_array', $file_array);
	}

	public function action_ajax_edit()
	{
		$this->output = array(
			'posted' => $_POST,
		);
		$error = FALSE;
		$object_id = empty($_POST['object_id']) ? $this->request->param('id') : (int) $_POST['object_id'];
		if ( empty($object_id))
		{
			$object_id = Model_Sequence::nextval();
		}
		$file_data = array(
			'_id' => '/' . DOMAINNAME . '/' . $object_id . '/' . URLify::filter($_POST['name']),
			'object_id' => $object_id,
			'category_id' => 0,
			'status' => $_POST['status'],
			'name' => $_POST['name'],
			'description' => $_POST['description'],
			'tags' => $_POST['tags'],
		);
		$result = File::update($file_data, $error);

		if ($result)
		{
			$this->output['redirect_url'] = URL::Site(route::get('default')->uri(array('controller'=>'file', 'action'=>'edit', 'id'=>$object_id)), TRUE);
			$this->output['message'] = __('File information updated successfully');
			$this->output['dismiss_timer'] = 2;
		}
		$this->output['error'] = $error;
		$this->output['result'] = $result;
	}
}
