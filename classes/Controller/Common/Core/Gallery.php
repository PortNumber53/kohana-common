<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_Gallery
 */
class Controller_Common_Core_Gallery extends Controller_Website
{
    public function action_index()
    {
        $this->action_view();
    }

    public function action_browse()
    {
        $page = (int)Arr::path($_GET, 'page', 1);
        $main = 'gallery/browse';
        View::bind_global('main', $main);

        $limit = 15;
        $offset = ($page - 1) * $limit;;
        $sort = array(
            'created_at' => 'DESC',
        );
        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $filtered_gallery = Gallery::filter($filter, $sort, $limit, $offset);

        /* $pagination = Pagination::factory(array(
            'current_page' => array('source' => 'query_string', 'key' => 'page'),
            'total_items' => $filtered_gallery['count'],
            'items_per_page' => $limit,
            'view' => 'pagination/floating',
            'auto_hide' => false,
        ));
        $page_links = $pagination->render();*/
        $page_links = 'links';

        View::bind_global('gallery_array', $filtered_gallery['rows']);
        View::bind_global('page_links', $page_links);
    }

    public function action_edit()
    {
        $object_id = $this->request->param('id');
        $gallery_data = Gallery::get_by_id($object_id);
        if (!$gallery_data) {
            $gallery_data = Gallery::getEmptyRow();
        }
        View::bind_global('gallery_data', $gallery_data);

        $main = 'gallery/edit';
        View::bind_global('main', $main);

        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $gallery_array = Gallery::filter($filter);
        View::bind_global('gallery_data', $gallery_data);
    }

    public function action_ajax_edit()
    {
        $this->output = array(
            'posted' => $_POST,
        );
        $error = false;
        $object_id = empty($_POST['object_id']) ? $this->request->param('id') : (int)$_POST['object_id'];
        if (empty($object_id)) {
            $object_id = Model_Sequence::nextval();
        }
        $gallery_data = array(
            '_id' => '/' . DOMAINNAME . '/' . $object_id . '/' . URLify::filter($_POST['name']),
            'object_id' => $object_id,
            'category_id' => 0,
            'status' => $_POST['status'],
            'name' => $_POST['name'],
            'tags' => $_POST['tags'],
        );
        if (!empty($_POST['description'])) {
            $gallery_data['description'] = $_POST['description'];
        }
        $result = Gallery::update($gallery_data, $error);

        if ($result) {
            $this->output['redirect_url'] = URL::Site(route::get('default')->uri(array(
                'controller' => 'gallery',
                'action' => 'edit',
                'id' => $object_id
            )), true);
            $this->output['message'] = __('Gallery information updated successfully');
            $this->output['dismiss_timer'] = 2;
        }
        $this->output['error'] = $error;
        $this->output['result'] = $result;
    }

    public function action_manage()
    {
        $object_id = $this->request->param('id');
        $gallery_data = Gallery::getById($object_id);

        $model_picture = new Model_Picture();

        $picture_array = $model_picture->getDataByParentId($gallery_data['galleryid']);

        foreach ($picture_array['rows'] as $key => $picture_data) {
            $path_parts = pathinfo($picture_data['image_filepath']);
            $picture_array['rows'][$key]['image_url'] = Url::Site(Route::get('image-actions')->uri(array(
                'pictureid' => $picture_data['pictureid'],
                'request' => "gallery/" . $picture_data['folder'] . '/' . $path_parts['filename'],
                'type' => $path_parts['extension'],
            )), true);
        }

        View::bind_global('gallery_data', $gallery_data);

        $main = 'gallery/manage';
        View::bind_global('main', $main);

        $filter = array(//array('account_id', '=', $account_data['object_id']),
        );
        $gallery_array = Gallery::filter($filter);
        View::bind_global('gallery_array', $gallery_array);
        View::bind_global('picture_array', $picture_array);
    }

    public function action_ajax_manage()
    {
        $error = false;
        $this->output = array(
            'posted' => $_POST,
        );

        $this->output['error'] = $error;
        $result = Gallery::check_permission(array(
            'owner' => '',
            'object' => 'gallery',
            'object_id' => (int)$_POST['what'],
        ), $error);
        if ($result) {
            $this->output['redirect_url'] = URL::Site(Route::get('default')->uri(array(
                'controller' => 'gallery',
                'action' => 'manage',
                'id' => (int)$_POST['what'],
            )), true);
        }

    }

    public function action_ajax_update()
    {
        $error = false;
        $this->output = array(
            'posted' => $_POST,
        );

        $object_id = $this->request->param('id');
        $file_list_array = array();
        if (!empty($_POST['file_list']) && is_array($_POST['file_list'])) {
            foreach ($_POST['file_list'] as $file_sent) {
                $file_list_array[] = array(
                    'image' => array(
                        'url' => $file_sent,
                    ),
                );
            }
        }
        $gallery_data = array(
            'galleryid' => $object_id,
            'categoryid' => 0,
            'status' => $_POST['status'],
            'name' => $_POST['name'],
            'tags' => $_POST['tags'],
            'file_list' => $file_list_array,
        );
        $result = Gallery::update($gallery_data, $error);

        if ($result) {
        }

        $this->output['error'] = $error;
    }
}
