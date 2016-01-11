<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Content
 */
class Content extends Abstracted
{
    protected static $data = array();

    public static function factory()
    {
        $obj = new self();
        $obj::$data = array();

        //$obj::$template_file = $template_file_name;
        //echo $obj::$template_file;

        //if (empty(self::$template_file))
        //{
        //	self::$template_file = 'frontend';
        //}/
        //$this->template = 'template/' . $this->template_name . '/' . $this->template_file;

        return $obj;
    }

    public static function get_author($_id)
    {
        return self::$sample_accounts[$_id];
    }
}
