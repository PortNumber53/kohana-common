<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 6/9/2015
 * Time: 9:11 AM
 */

class Controller_Common_Core_Shopping extends Controller_Website
{

    public function action_cart()
    {
        $main = 'shopping/cart';

        View::bind_global('main', $main);
    }

    public function action_checkout()
    {
        $main = 'shopping/checkout';

        View::bind_global('main', $main);
    }

    public function action_wishlist()
    {
        $main = 'shopping/wishlist';

        View::bind_global('main', $main);
    }

}
