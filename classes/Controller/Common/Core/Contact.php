<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/9/2015
 * Time: 8:38 AM
 */

class Controller_Common_Core_Contact extends Controller_Website
{

    public function action_form()
    {
        $main = 'contact/form';


        View::bind_global('main', $main);
    }



}
