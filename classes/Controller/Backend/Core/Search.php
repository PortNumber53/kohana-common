<?php

/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 7/9/2015
 * Time: 9:27 AM
 */
class Controller_Backend_Core_Search extends Controller_Backend_Core_Backend
{

    /**
     * This is the function that actually performs the search and return results
     * You want to override it at application level
     * @param $query
     * @return array
     */
    protected function search($query)
    {
        return array('query' => $query);
    }

    /**
     * This function responds to the ajax request
     */
    public function action_ajax_main()
    {
        $query = $this->request->query('s');
        $this->output = array(
            'results' => $this->search($query),
        );
    }

}