<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_Blog
 */
class Controller_Common_Core_Blog extends Controller_Website
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
        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $filter = Gallery::filter($filter, $sort, $limit, $offset);

        $data = array();
        foreach ($filter['rows'] as $row) {
            $id = $row['object_id'];
            $slug = URLify::filter($row['name']);

            $data[] = array(
                'url' => URL::Site(Route::get('blog-actions')->uri(array('id' => $id, 'slug' => $slug,)), true),
            );
        }
        View::bind_global('data', $data);
    }

    public function action_view()
    {
        $object_id = Request::current()->param('id');
        $slug = Request::current()->param('slug');

        if (empty($object_id)) {
            $page = (int)Arr::path($_GET, 'page');
            if (empty($page)) {
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
            if (empty($filtered_gallery['rows'])) {
                throw HTTP_Exception::factory(404, 'We could not find any post, chief!');
            }

            $galleryid = Request::current()->param('id');

            $model_gallery = new Model_Gallery();
            $filtered_gallery = $model_gallery->filter($filter, $sort, $limit, $offset);

            $model_picture = new Model_Picture();

            foreach ($filtered_gallery['rows'] as $key => &$row) {
                $row['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array(
                    'id' => $row['galleryid'],
                    'slug' => URLify::filter($row['name']),
                )), true);
                if (substr($row['canonical_url'], -1) != '/') {
                    $row['canonical_url'] = $row['canonical_url'] . '/';
                }
                $picture_data = $model_picture->getDataByParentId($row['galleryid']);

                if (count($picture_data['rows']) > 0) {
                    $first_image = array_shift($picture_data['rows']);
                    $path_parts = pathinfo($first_image['image_filepath']);
                    $filtered_gallery['rows'][$key]['image_url'] = Url::Site(Route::get('image-actions')->uri(array(
                        'pictureid' => $row['galleryid'],
                        'request' => "gallery/" . $first_image['folder'] . '/' . $path_parts['filename'],
                        'type' => $path_parts['extension'],
                    )), true);
                } else {
                    $row['image_url'] = 'BROKENID:'.$row['galleryid'];
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

            View::bind_global('filtered_gallery', $filtered_gallery);

            //Canonical URL
            //$this->canonical_url = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URLify::filter($gallery_data['name']))), TRUE) . '/';
        } else {
            $galleryid = Request::current()->param('id');

            $model_gallery = new Model_Gallery();
            $gallery_data = $model_gallery->getDataById($galleryid);

            $model_picture = new Model_Picture();
            $picture_data = $model_picture->getDataByParentId($galleryid);

            foreach ($picture_data['rows'] as $key => $row) {
                $path_parts = pathinfo($row['image_filepath']);
                $picture_data['rows'][$key]['image_url'] = Url::Site(Route::get('image-actions')->uri(array(
                    'pictureid' => $galleryid,
                    'request' => "gallery/" . $row['folder'] . '/' . $path_parts['filename'],
                    'type' => $path_parts['extension'],
                )), true);
            }

            //Canonical URL
            $gallery_data['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array(
                    'id' => $gallery_data['galleryid'],
                    'slug' => URLify::filter($gallery_data['name'])
                )), true) . '/';
            if (substr($gallery_data['canonical_url'], -1) != '/') {
                $gallery_data['canonical_url'] = $gallery_data['canonical_url'] . '/';
            }
            if (empty($slug) || $_SERVER['SCRIPT_URI'] != $gallery_data['canonical_url']) {
                $this->redirect($gallery_data['canonical_url'], 301);
            }

            View::bind_global('gallery_data', $gallery_data);
            View::bind_global('picture_data', $picture_data);
            $this->canonical_url = $gallery_data['canonical_url'];
            $page_title = Arr::path($gallery_data, 'name', '');
            View::add_global('og:tags', 'og:title', $page_title);
            View::add_global('og:tags', 'og:url', $gallery_data['canonical_url']);
            View::add_global('og:tags', 'og:image',
                URL::Site('/' . Arr::path($gallery_data, 'file_list.0.image.url'), true));

            View::bind_global('page_title', $page_title);

            $main = 'gallery/blog/post';
            View::bind_global('main', $main);

            View::set_global('single_post', true);
        }
    }

}
