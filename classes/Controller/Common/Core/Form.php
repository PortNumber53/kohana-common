<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Common_Core_Form
 */
class Controller_Common_Core_Form extends Controller_Website
{

    public function action_start()
    {
        $main = 'form/start';
        View::bind_global('main', $main);
    }

}
