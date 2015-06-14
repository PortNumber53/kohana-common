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

    public function action_browse()
    {
        $categorySeo = '';
        $categoryData = false;
        $productData = false;
        $pictureData = false;

        $request = $this->request->param('id');

        $pattern = '/item_([0-9]+)([-])([a-z0-9]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $productId = $matches[1];
            $productData = Model_Product::getDataById($productId);
            $categoryData = Model_Category::getDataById($productData['categoryid']);

            $pictureData = Model_Picture::getDataByParentId($productData['productid']);
        }

        $pattern = '/cat_([a-z\-]+)/';
        if (preg_match($pattern, $request, $matches)) {
            $categorySeo = $matches[1];
        }

        if (!empty($categorySeo)) {
            $categoryData = Model_Category::getDataBySeo($categorySeo);
            $productData = Model_Product::getDataByParentId($categoryData['categoryid']);
        }

        if (isset($productData['rows'])) {
            $main = 'shopping/browse';
        } else {
            $main = 'shopping/product_detail';
        }
        View::bind_global('categoryData', $categoryData);
        View::bind_global('productData', $productData);
        View::bind_global('pictureData', $pictureData);
        View::bind_global('main', $main);
    }

}
