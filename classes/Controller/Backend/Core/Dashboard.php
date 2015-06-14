<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/11/2015
 * Time: 1:08 AM
 */
class Controller_Backend_Core_Dashboard extends Controller_Backend_Core_Backend
{

    public function action_main()
    {
        $main = 'backend/dashboard/main';

        View::bind_global('main', $main);
    }
}
