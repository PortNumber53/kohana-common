<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/16/13
 * Time: 1:04 PM
 * Something meaningful about this gallery
 *
 */


class Controller_Common_Core_Gallery extends Controller_Common_Core_Website
{
	public function action_browse()
	{
		$main = 'gallery/browse';
		View::set_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$gallery_array = Gallery::filter($filter);
		View::set_global('gallery_array', $gallery_array);
	}

	public function action_edit()
	{
		$object_id = $this->request->param('id');
		$gallery_data = Gallery::get_by_object_id($object_id);
		if ( ! $gallery_data)
		{
			$gallery_data = Gallery::get_empty_row();
		}
		View::set_global('gallery_data', $gallery_data);

		$main = 'gallery/edit';
		View::set_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$gallery_array = Gallery::filter($filter);
		View::set_global('gallery_array', $gallery_array);
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
		$gallery_data = array(
			'_id' => '/' . DOMAINNAME . '/' . $object_id . '/' . URL::title($_POST['name'], '-', TRUE),
			'object_id' => $object_id,
			'category_id' => 0,
			'status' => $_POST['status'],
			'name' => $_POST['name'],
			'tags' => $_POST['tags'],
		);
		if (! empty($_POST['description']))
		{
			$gallery_data['description'] = $_POST['description'];
		}
		$result = Gallery::update($gallery_data, $error);

		if ($result)
		{
			$this->output['redirect_url'] = URL::Site(route::get('default')->uri(array('controller'=>'gallery', 'action'=>'edit', 'id'=>$object_id)), TRUE);
			$this->output['message'] = __('Gallery information updated successfully');
			$this->output['dismiss_timer'] = 2;
		}
		$this->output['error'] = $error;
		$this->output['result'] = $result;
	}


	public function action_manage()
	{
		$object_id = $this->request->param('id');
		$gallery_data = Gallery::get_by_object_id($object_id);
		if ( ! $gallery_data)
		{
			$gallery_data = Gallery::get_empty_row();
		}
		View::set_global('gallery_data', $gallery_data);

		$main = 'gallery/manage';
		View::set_global('main', $main);

		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$gallery_array = Gallery::filter($filter);
		View::set_global('gallery_array', $gallery_array);
	}

	public function action_ajax_manage()
	{
		$error = FALSE;
		$this->output = array(
			'posted' => $_POST,
		);

		$this->output['error'] = $error;
		$result = Gallery::check_permission(array(
			'owner' => '',
			'object' => 'gallery',
			'object_id' => (int) $_POST['what'],
		), $error);
		if ($result)
		{
			$this->output['redirect_url'] = URL::Site(Route::get('default')->uri(array('controller'=>'Gallery', 'action'=>'manage', 'id'=>(int) $_POST['what'], )), TRUE);
		}

	}


	public function action_ajax_update()
	{
		$error = FALSE;
		$this->output = array(
			'posted' => $_POST,
		);

		$object_id = $this->request->param('id');
		$gallery_data = array(
			'_id' => '/' . DOMAINNAME . '/' . $object_id . '/' . URL::title($_POST['name'], '-', TRUE),
			'object_id' => $object_id,
			'category_id' => 0,
			'status' => $_POST['status'],
			'name' => $_POST['name'],
			'tags' => $_POST['tags'],
			'file_list' => $_POST['file_list'],
		);
		$result = Gallery::update($gallery_data, $error);

		if ($result)
		{

		}

		$this->output['error'] = $error;
	}
}