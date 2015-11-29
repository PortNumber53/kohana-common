<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Backend_Core_Dashboard
 */
class Controller_Backend_Core_Dashboard extends Controller_Backend_Core_Backend
{
    public function action_main()
    {
        $main = 'backend/dashboard/main';

        View::bind_global('main', $main);
    }
}
