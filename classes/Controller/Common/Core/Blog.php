<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/19/13
 * Time: 12:18 AM
 * Something meaningful about this file
 *
 */

class Controller_Common_Core_Blog extends Controller_Common_Core_Website
{
	public function action_view()
	{
		$object_id = Request::current()->param('id');
		$slug = Request::current()->param('slug');

		if (is_null($object_id))
		{
			View::set_global('main', 'gallery/blog/scroll');
			$filter = array(
				//'/' . DOMAINNAME . '/' . $object_id . '/' . URL::title($_POST['name'], '-', TRUE),
				//array('object_id', '=', $object_id, ),
				//array('account_id', '=', $account_data['object_id']),
			);
			$gallery_data = Gallery::filter($filter);
			if (empty($gallery_data))
			{
				throw HTTP_Exception::factory(404, 'We could not find any post, chief!');
			}
			//$gallery_data = array_shift($gallery_data);
			View::set_global('gallery_data', $gallery_data);
			//Canonical URL
			//$this->canonical_url = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URL::title($gallery_data['name']))), TRUE) . '/';

		}
		else
		{
			$page = 0;
			$item_per_page = 1;

			//$object_id = empty($_POST['object_id']) ? $this->request->param('id') : (int) $_POST['object_id'];
			$filter = array(
				//'/' . DOMAINNAME . '/' . $object_id . '/' . URL::title($_POST['name'], '-', TRUE),
				array('object_id', '=', $object_id, ),
				//array('account_id', '=', $account_data['object_id']),
			);
			$gallery_data = Gallery::filter($filter);
			if (empty($gallery_data))
			{
				throw HTTP_Exception::factory(404, 'We could not find that post, chief!');
			}
			$gallery_data = array_shift($gallery_data);

			View::set_global('gallery_data', $gallery_data);

			//Canonical URL
			$this->canonical_url = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URL::title($gallery_data['name']))), TRUE) . '/';

			View::set_global('main', 'gallery/blog/post');
		}
	}

}