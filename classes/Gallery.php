<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Gallery
 */
class Gallery extends Abstracted
{
    const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

    protected static $data = array();

    public static function factory()
    {
        $obj = new self();
        $obj::$data = array();

        return $obj;
    }

    public static function get_empty_row()
    {
        $ogallery = new Model_Gallery();
        return $ogallery::$_columns;
    }

    public static function get_author($_id)
    {
        return self::$sample_accounts[$_id];
    }


    public static function reset($data, &$error)
    {
        $data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
        $gallery = new Model_Gallery();

        if (!$exists = self::progallery($data['_id'])) {
            $error = array(
                'error' => 255,
                'message' => __('Account does not exist'),
            );
            return false;
        } else {
            //Add hash to account
            $exists['hash'] = md5('123mudar');
            $gallery->save($exists, $error);
            return true;
        }
    }

    public static function deleteById($primary_id, &$error)
    {
        $ogallery = new Model_Gallery();
        $result = $ogallery->deleteById($primary_id, $error);

        return $result;
    }

    public static function delete_by_object_id($object_id, &$error)
    {
        $ogallery = new Model_Gallery();
        $result = $ogallery->delete_by_object_id($object_id, $error);

        return $result;
    }
}
