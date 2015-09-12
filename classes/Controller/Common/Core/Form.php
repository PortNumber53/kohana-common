<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 8/2/2015
 * Time: 4:51 PM
 */
class Controller_Common_Core_Form extends Controller_Website
{

    public function action_start()
    {
        $main = 'form/start';
        View::bind_global('main', $main);
    }

}
