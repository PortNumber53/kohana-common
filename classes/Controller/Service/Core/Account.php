<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Service_Core_Account
 */
class Controller_Service_Core_Account extends Controller_Service_Core_Service
{

    public function action_actions()
    {
        echo "do an ajax call";
    }

    public function action_ajax_actions()
    {
        $profile = Account::profile();
        if ($profile['profile'] === 'ADMIN') {
            $this->output = array('menu' => Website::get('logged_menu', array()));

        } else {
            $this->output = array('menu' => array(),);
        }
    }
}
