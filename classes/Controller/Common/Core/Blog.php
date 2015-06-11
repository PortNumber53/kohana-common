<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/19/13
 * Time: 12:18 AM
 * Something meaningful about this file
 *
 */

class Controller_Common_Core_Blog extends Controller_Common_Core_Website
{

    public function action_landing()
    {
        $main = 'blog/landing';

        View::bind_global('main', $main);
    }

    public function action_post()
    {
        $main = 'blog/post';

        View::bind_global('main', $main);
    }

	public function generate_txt()
	{
		$page = Request::current()->param('page', 1);
		$limit = 1000;
		$offset = ($page - 1) * $limit;;
		$sort = array(
			'object_id' => 'ASC',
		);
		$filter = array(
			//array('account_id', '=', $account_data['object_id']),
		);
		$filter = Gallery::filter($filter, $sort, $limit, $offset);

		$data = array();
		foreach ($filter['rows'] as $row)
		{
			$id = $row['object_id'];
			$slug = URLify::filter($row['name']);

			$data[] = array(
				'url' => URL::Site(Route::get('blog-actions')->uri(array('id'=>$id, 'slug'=>$slug, )), TRUE),
			);
		}
		View::bind_global('data', $data);
	}

	public function action_view()
	{
		$object_id = Request::current()->param('id');
		$slug = Request::current()->param('slug');

		if (empty($object_id))
		{
			$page = (int) Arr::path($_GET, 'page');
			if (empty($page))
			{
				$page = Request::current()->param('page', 1);
			}
			View::set_global('page_title', 'Gallery page: ' . $page);

			$main = 'gallery/blog/scroll';
			View::bind_global('main', $main);
			$filter = array(
				//'/' . DOMAINNAME . '/' . $object_id . '/' . URLify::filter($_POST['name'], '-', TRUE),
				//array('object_id', '=', $object_id, ),
				//array('account_id', '=', $account_data['object_id']),
			);
			$limit = 15;
			$sort = array(
				'created_at' => 'desc',
			);
			$offset = ($page - 1) * $limit;
			$filtered_gallery = Gallery::filter($filter, $sort, $limit, $offset);
			if (empty($filtered_gallery['rows']))
			{
				throw HTTP_Exception::factory(404, 'We could not find any post, chief!');
			}
			foreach ($filtered_gallery['rows']as $key=>&$row)
			{
				$row['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array('id'=>$row['object_id'], 'slug'=>URLify::filter($row['name']), )), TRUE);
				if (substr($row['canonical_url'], -1) != '/')
				{
					$row['canonical_url'] = $row['canonical_url'] . '/';
				}
			}

			$pagination = Pagination::factory(array(
				'current_page'      => array('source' => 'route', 'key' => 'page'),
				'total_items'       => $filtered_gallery['count'],
				'items_per_page'    => $limit,
				'view'              => 'pagination/basic',
				'auto_hide'         => FALSE,
			));
			$page_links = $pagination->render();
			View::bind_global('page_links', $page_links);

			//$gallery_data = array_shift($gallery_data);
			View::bind_global('filtered_gallery', $filtered_gallery);
			//Canonical URL
			//$this->canonical_url = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URLify::filter($gallery_data['name']))), TRUE) . '/';
		}
		else
		{
			$gallery_data = Gallery::get_by_object_id($object_id);
			if (empty($gallery_data))
			{
				throw HTTP_Exception::factory(404, 'We could not find that post, chief!');
			}

			//Canonical URL
			$gallery_data['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URLify::filter($gallery_data['name']))), TRUE) . '/';
			if (substr($gallery_data['canonical_url'], -1) != '/')
			{
				$gallery_data['canonical_url'] = $gallery_data['canonical_url'] . '/';
			}
			//echo '<pre>';var_dump($_SERVER);echo'</pre>';
			//echo '<pre>';var_dump($gallery_data); echo '</pre>';die();
			if (empty($slug) || $_SERVER['SCRIPT_URI'] != $gallery_data['canonical_url'])
			{
				$this->redirect($gallery_data['canonical_url'], 301);
			}

			View::bind_global('gallery_data', $gallery_data);
			$this->canonical_url = $gallery_data['canonical_url'];
			$page_title = Arr::path($gallery_data, 'name', '');
			View::add_global('og:tags', 'og:title', $page_title);
			View::add_global('og:tags', 'og:url', $gallery_data['canonical_url']);
			View::add_global('og:tags', 'og:image', URL::Site('/' . Arr::path($gallery_data, 'file_list.0.image.url'), TRUE));

			View::bind_global('page_title', $page_title);

			$main = 'gallery/blog/post';
			View::bind_global('main', $main);

			View::set_global('single_post', TRUE);
		}
	}

}
