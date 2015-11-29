<?php

/**
 * Date: 5/14/13
 * Time: 12:58 PM
 */
class Controller_Common_Core_Website extends Controller_Template
{
    public $template_name = '';
    public static $template_file = 'frontend';

    public static $template_mapping = array(
        'controller:action' => 'otherend',
    );

    public $auth_required = false;
    public $auth_actions = array();

    public $output = null;
    public $json = null;
    public $canonical_url = null;

    protected $_cookie = null;

    public static $settings = array();
    protected $frontend_cookie = null;
    protected $backend_cookie = null;

    public static $account = null;

    protected $_per_page = 9;

    protected $page_title = '';


    public function __construct(Request $request, Response $response)
    {
        $session = Session::instance();

        $dotSettings = defined(WEBSITE) ? array() : json_decode(WEBSITE, true);

        $settings = Kohana::$config->load('website')->as_array();
        self::$settings = array_merge($settings, $dotSettings);
        View::set_global('debug', Arr::path(self::$settings, 'debug', false));

        setlocale(LC_MONETARY, 'en_US');

        //$this->frontend_cookie = json_decode(Cookie::get(Constants::FE_COOKIE), true);
        //$this->backend_cookie = json_decode(Cookie::get(Constants::BE_COOKIE), true);

        parent::__construct($request, $response);

        //Check for SEO stuff
        if ($data = SEO::checkRedirection($this->request)) {
            $this->redirect($data['to_url'], $data['status_code']);
        }

        //If a user is not logged in and authentication is required:
        if ($this->auth_required && !Auth::instance()->logged_in()) {
            $this->redirect('/login?url=' . URL::site(Request::current()->uri()));
        }
        if ($this->auth_required && $account = Auth::instance()->logged_in()) {
            if ($this->auth_required !== $account['profile']) {
                $this->redirect('/login?profile=wrong&url=' . URL::site(Request::current()->uri()));
            }
        }

        if (in_array($this->request->action(), $this->auth_actions)) {
            if (!Account::factory()->isLoggedIn()) {
                echo $this->request->action() . ' requires Authentication!';
                $this->redirect('/login?url=' . URL::site(Request::current()->uri()));
            }
            //$this->template_file = 'backend';
        };


        if (strpos(strtolower($this->request->headers('accept')),
                'application/json') !== false || $this->request->is_ajax() || !empty($this->json)
            || (strtolower($this->request->controller()) == 'upload' && strtolower($this->request->action()) == 'receive')
        ) {
            $this->json = json_decode(file_get_contents('php://input'), true);
            $this->auto_render = false;
            $this->request->action('ajax_' . $this->request->action());
            $this->response->headers('content-type', 'application/json');
        }
    }


    public function before()
    {
        $this->_cookie = json_decode(Cookie::get('site'), true);

        if (empty($this->template_name)) {
            // Old config format template.selected is a string, not an array
            $selected_template = Website::get('template.selected');
            if (is_string($selected_template)) {
                $this->template_name = $selected_template;
            } else {
                $this->template_name = Website::get('template.selected.' . static::$template_file, '__NOT_FOUND__');
            }
        }

        if (!empty(static::$template_mapping)) {
            $key = strtolower(Request::current()->controller()) . ':' . strtolower(Request::current()->action());
            if (isset(static::$template_mapping[$key])) {
                static::$template_file = static::$template_mapping[$key];
            }
        }
        if (empty(static::$template_file)) {
            static::$template_file = 'frontend';
        }
        $new_template = 'template/' . $this->template_name . '/' . static::$template_file;
        if (!Kohana::find_file('views', $new_template)) {
            $new_template = 'template/default/' . static::$template_file;
        }
        Website::set_template($this->template_name);

        static::$account = Account::factory()->getLoggedAccount();

        $this->template = $new_template;
        parent::before();





        View::bind_global('account', static::$account);
        View::bind_global('cookie_data', $this->_cookie);

        View::set_global(
            'current_path',
            strtolower(Request::current()->directory() . '/' . Request::current()->controller() . '/' . Request::current()->action())
        );
        View::set_global(
            'current_url',
            URL::site(Request::detect_uri(), true) . URL::query()
        );

        if ($this->auto_render) {
            $per_page_array = array(
                9 => '9 per page',
                15 => '15 per page',
                21 => '21 per page',
                30 => '30 per page',
            );
            View::bind_global('per_page_array', $per_page_array);
            View::bind_global('per_page', $this->_per_page);

            View::bind_global('page_title', $this->page_title);

            $current_url = URL::Site(Request::detect_uri(), true);
            $menu = array();
            $menu['content_url'] = URL::Site(Route::get('default')->uri(array(
                'controller' => 'content',
                'action' => 'browse',
            )), true);
            $menu['product_url'] = URL::Site(Route::get('default')->uri(array(
                'controller' => 'product',
                'action' => 'browse',
            )), true);
            $menu['gallery_url'] = URL::Site(Route::get('blog-actions')->uri(array('',)), true);
            $menu['profile_url'] = URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true);
            $menu['privacy_url'] = URL::Site(Route::get('html-content')->uri(array(
                'request' => 'privacy-policy',
                'type' => 'html'
            )), true);
            View::bind_global('menu', $menu);
            View::bind_global('current_url', $current_url);

            View::set_global('param', Request::current()->param());
            View::set_global('title', '');
            View::set_global('content', '');
            View::set_global('language', Kohana::$config->load('contentus.default_language'));
            View::set_global('body_class', '');
            View::set_global('meta', array());

            View::set_global('styles', array());
            View::set_global('scripts', array());
            View::set_global('breadbrumbs', array());

            $sections = Website::get('template.' . $this->template_name . '.' . static::$template_file . '.sections',
                array());
            $template_sections = array();
            foreach ($sections as $section) {
                if (!empty($section['view'])) {
                    $template_sections[$section['name']] = $section['view'];
                }
            }
            View::bind_global('template', $template_sections);
        }
    }

    public function after()
    {
        View::bind_global('site_settings', self::$settings);
        if ($this->auto_render) {
            View::bind_global('canonical_url', $this->canonical_url);
            if (empty($this->canonical_url)) {
                $this->canonical_url = URL::site('/', true);
            }
            View::set_global('current_url', URL::site(Request::factory()->current()->uri(), true));

            $route_info = array_merge(Request::factory()->current()->param(),
                array(
                'directory' => Request::factory()->current()->directory(),
                'controller' => Request::factory()->current()->controller(),
                'action' => Request::factory()->current()->action(),
            ));
            View::set_global('route_info', $route_info);

            $styles = Website::template('style', array());
            $scripts = Website::template('script', array());

            $custom_styles = Website::template('style', array());
            $custom_scripts = Website::template('script', array());

            View::set_global('styles', array_merge($this->template->styles, $styles, $custom_styles));
            View::set_global('scripts', array_keys(array_merge($this->template->scripts, $scripts, $custom_scripts)));

        } else {
            $content_type = Arr::path($this->response->headers(), 'content-type', 'text/html');
            switch ($content_type) {
                case 'application/json':
                    $this->response->body(json_encode($this->output));
                    break;
                default:
                    $this->response->body($this->output);
            }

        }
        Cookie::set('site', json_encode($this->_cookie));
        parent::after();
    }

}
