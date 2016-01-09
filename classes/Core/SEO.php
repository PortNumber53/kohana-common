<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Core_SEO
 */
class Core_SEO
{
    public static function checkRedirection($request)
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        $seo_config = Kohana::$config->load('seo')->as_array();
        foreach (Arr::path($seo_config, 'regex', array()) as $regex) {
            if (preg_match($regex['regex'], $request_uri, $matches)) {
                $route_name = $regex['route'];
                $params = array();
                foreach ($regex['params'] as $param_name => $param_index) {
                    $params[$param_name] = $matches[$param_index];
                }
                $to_url = URL::Site(Route::get($route_name)->uri($params), true) . '/';
                $data = array(
                    'to_url' => $to_url,
                    'status_code' => 301,
                );
            }
        }

        if (empty($data)) {
            $_id = '/' . DOMAINNAME . $request_uri;

            $seo = new Model_SEO();
            $data = $seo->get_by_id($_id);
        }

        if (empty($data)) {
            $routes = Route::all();
            $matched = false;
            foreach ($routes as $route) {
                $params = $route->matches(Request::factory(URL::site($request_uri, false)));
                if ($params) {
                    $matched = $params;
                    break;
                }
            }

            $_id = '/' . DOMAINNAME;
            $data = $seo->get_by_id($_id);

            //Extra subfolders from referrer
            if ($matched['controller'] == 'Account') {

            }
        }

        return $data;
    }
}
