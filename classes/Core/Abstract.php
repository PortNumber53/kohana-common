<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Interface iAbstractTable
 */
interface iAbstractTable
{
    public static function get_by_id($_id, &$options = array());

    public static function get_by_object_id($object_id, &$options = array());

    public static function check_permission($data, &$option = array());

    public static function filter($filter = array(), $sort = array(), $limit = array(), $offset = array());

    public static function update(&$data, &$error);
}

abstract class Core_Abstract implements iAbstractTable
{
    public static function get_by_id($_id, &$options = array())
    {
        $class = 'Model_' . get_called_class();
        $oTable = new $class;
        if (substr($_id, 0, 1) != '/') {
            $_id = "/$_id";
        }
        if (strpos($_id, DOMAINNAME) === false) {
            $_id = '/' . DOMAINNAME . $_id;
        }
        echo "$class ID: $_id";
        $result = $oTable->get_by_id($_id);
        return $result;
    }

    public static function get_by_object_id($object_id, &$options = array())
    {
        $class = 'Model_' . get_called_class();
        $oTable = new $class;
        $result = $oTable->get_by_object_id($object_id);
        return $result;
    }

    public static function check_permission($data, &$option = array())
    {
        // TODO: Implement check_permission() method.

        //$owner_id  = $data['owner_id'];
        //$entity    = $data['entity'];
        //$object_id = $data['obtect_id'];
        return true;
    }

    public static function filter($filter = array(), $sort = array(), $limit = array(), $offset = array())
    {
        $class = 'Model_' . get_called_class();
        $oTable = new $class;
        $result = $oTable->filter($filter, $sort, $limit, $offset);

        return $result;
    }


    public static function update(&$data, &$error)
    {
        $class = 'Model_' . get_called_class();
        $oTable = new $class;

        $author = Account::profile();
        if ($author) {
            $data['author_id'] = $author['object_id'];
        }
        //Update content
        if ($result = $oTable->save($data, $error)) {
            return true;
        } else {
            return false;
        }
    }

}
