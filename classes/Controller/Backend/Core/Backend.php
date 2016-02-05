<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Backend_Core_Backend
 */
class Controller_Backend_Core_Backend extends Controller_Website
{
    public static $template_file = 'backend';

    public $auth_required = 'admin';
    public $auth_actions = array();


    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $domain_name = Arr::path(self::$settings, 'website.domain_name');
        if (Arr::path(self::$settings, 'website.fake_production')) {
            $domain_name = Arr::path(self::$settings, 'website.old_production_domain_name');
        }

        if ($this->auto_render) {
            View::set_global('debug', Arr::path(self::$settings, 'debug', false));
            View::bind_global('site_settings', self::$settings);
            View::bind_global('domain_name', $domain_name);

            $header = 'backend/modules/header';
            View::bind_global('header', $header);
            $sidebar = 'backend/modules/sidebar';
            View::bind_global('sidebar', $sidebar);
            $top_nav_bar = 'backend/modules/top_nav_bar';
            View::bind_global('top_nav_bar', $top_nav_bar);
            $footer = 'backend/modules/footer';
            View::bind_global('footer', $footer);
        }
    }

}
