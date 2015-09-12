<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 8/2/2015
 * Time: 7:33 PM
 */
class Controller_Common_Core_Image extends Controller_Website
{
    public $auto_render = false;

    public function action_upload()
    {
        $this->response->headers('content-type', 'application/json');

        $this->output['_POST'] = $_POST;
        $this->output['_FILES'] = $_FILES;
    }

    public function action_ajax_upload()
    {
        $this->response->headers('content-type', 'application/json');
        $upload_root_folder = Arr::path(self::$settings, 'kohana.data_path');

        $pkid = isset($_POST['pkid']) ? (int) $_POST['pkid'] : 0;

        $relative_path = 'upload/trade/' . Arr::path(static::$account, 'accountid') . '/trade/' . $pkid .'/';

        $files = array();
        //var_dump($_FILES['files']);
        for ($counter = 0; $counter < count($_FILES['files']['tmp_name']); $counter++) {
            $file = array(
                'name' => $_FILES['files']['name'][$counter],
                'tmp_name' => $_FILES['files']['tmp_name'][$counter],
            );

            if (!is_dir("$upload_root_folder/$relative_path")) {
                mkdir("$upload_root_folder/$relative_path", 0775, true);
            }
            $clean_file_name = $file['name'];

            $target_file = "$upload_root_folder/$relative_path/$clean_file_name";

            if (move_uploaded_file($file['tmp_name'], $target_file)) {

                $filesize = filesize($target_file);
                $files[] = array(
                    'name' => $clean_file_name,
                    'size' => $filesize,
                    'url' => URL::site(Route::get('image-actions')->uri(array(
                        'request' => "$relative_path/$clean_file_name",
                        'type' => '',
                    )), true),
                    'thumbnailUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/' . $clean_file_name,
                    'deleteUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/' . $clean_file_name,
                    'deleteType' => 'DELETE',
                );
            } else {
                $filesize = filesize($target_file);
                $files[] = array(
                    'name' => $clean_file_name,
                    'size' => $filesize,
                    'url' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/' . $clean_file_name,
                    'thumbnailUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/' . $clean_file_name,
                    'deleteUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/' . $clean_file_name,
                    'deleteType' => 'DELETE',
                );
            }
        }

        $this->output['files'] = $files; /* array(
            array(
                'name' => 'picture1.jpg',
                'size' => 123456,
                'url' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/picture1.jpg',
                'thumbnailUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/picture1_small.jpg',
                'deleteUrl' => 'http://3rdgenerationco.dev/image/view/temp/something/other/else/picture1XX.jpg',
                'deleteType' => 'DELETE',
            ),
        );*/

    }
}