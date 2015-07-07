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

	public $auth_required = FALSE;
	public $auth_actions = array();

	public $output = null;
	public $canonical_url = null;

	public static $settings = array();

    public static $account = null;

	public function __construct(Request $request, Response $response)
	{
		$session = Session::instance();

        $dotSettings = empty(WEBSITE) ? array() : json_decode(WEBSITE, true);

		$settings = Kohana::$config->load('website')->as_array();
		self::$settings = array_merge($settings, $dotSettings);
		View::set_global('debug', Arr::path(self::$settings, 'debug', FALSE));

        setlocale(LC_MONETARY, 'en_US');

		parent::__construct($request, $response);

		//Check for SEO stuff
		if ($data = SEO::checkRedirection($this->request))
		{
			$this->redirect($data['to_url'], $data['status_code']);
		}

		//If a user is not logged in and authentication is required:
		if ($this->auth_required && ! Auth::instance()->logged_in())
			$this->redirect('/login?url='.URL::site(Request::current()->uri()));

		if (in_array($this->request->action(), $this->auth_actions))
		{
			if ( ! Account::factory()->isLoggedIn())
			{
				echo $this->request->action() . ' requires Authentication!';
				$this->redirect('/login?url='.URL::site(Request::current()->uri()));
			}
			//$this->template_file = 'backend';
		};

		$this->json = json_decode(file_get_contents('php://input'), TRUE);
		if (strpos(strtolower($this->request->headers('accept')), 'application/json') !== FALSE ||$this->request->is_ajax() || ! empty($this->json)
			|| (strtolower($this->request->controller()) == 'upload' && strtolower($this->request->action()) == 'receive') )
		{
			$this->auto_render = FALSE;
			$this->request->action('ajax_'.$this->request->action());
			$this->response->headers('content-type', 'application/json');
		}
	}


	public function before()
	{
        if (empty($this->template_name))
        {
            $this->template_name = Website::get('template.selected.' . static::$template_file, 'default');
        }

        if (! empty(static::$template_mapping))
        {
            $key = strtolower(Request::current()->controller()) . ':' . strtolower(Request::current()->action());
            if (isset(static::$template_mapping[$key]))
            {
                static::$template_file = static::$template_mapping[$key];
            }
        }
        if (empty(static::$template_file))
        {
            static::$template_file = 'frontend';
        }
        $new_template = 'template/' . $this->template_name . '/' . static::$template_file;
        if (! Kohana::find_file('views', $new_template))
        {
            $new_template = 'template/default/' . static::$template_file;
        }
        Website::set_template($this->template_name);

        $this->template = $new_template;
		parent::before();

        if (Account::factory()->isLoggedIn() && (static::$account = Account::factory()->profile())) {
        } else {
            static::$account = Account::factory()->createGuest();
        }
        //echo " ACCOUNT:";
        //var_dump(static::$account);die();
        View::bind_global('account', static::$account);
		if ($this->auto_render)
		{
			$current_url = URL::Site(Request::detect_uri(), TRUE);
			$menu = array();
			$menu['content_url'] = URL::Site(Route::get('default')->uri(array('controller'=>'content', 'action'=>'browse', )), TRUE);
			$menu['product_url'] = URL::Site(Route::get('default')->uri(array('controller'=>'product', 'action'=>'browse', )), TRUE);
			$menu['gallery_url'] = URL::Site(Route::get('blog-actions')->uri(array('',)), TRUE);
			$menu['profile_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
			$menu['privacy_url'] = URL::Site(Route::get('html-content')->uri(array('request'=>'privacy-policy', 'type'=>'html' )), TRUE);
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

			$sections = Website::get('template.'.$this->template_name.'.'.static::$template_file.'.sections', array());
			$template_sections = array();
			foreach ($sections as $section)
			{
				if ( ! empty($section['view']))
				{
					$template_sections[$section['name']] = $section['view'];
				}
			}
			View::bind_global('template', $template_sections);
		}
	}

	public function after()
	{
		if ($this->auto_render)
		{
			View::bind_global('canonical_url', $this->canonical_url);
			if ( empty($this->canonical_url))
			{
				$this->canonical_url = URL::site('/', TRUE);
			}
			View::set_global('current_url', URL::site(Request::factory()->current()->uri(), true));

			$styles = Website::template('style', array());
			$scripts = Website::template('script', array());

			$custom_styles = Website::template('style', array());
			$custom_scripts = Website::template('script', array());

			View::set_global('styles', array_merge($this->template->styles, $styles, $custom_styles));
			View::set_global('scripts', array_keys(array_merge($this->template->scripts, $scripts, $custom_scripts)));

			View::bind_global('site_settings', self::$settings);
		}
		else
		{
			$content_type = Arr::path($this->response->headers(), 'content-type', 'text/html');
			switch ($content_type)
			{
				case 'application/json':
					$this->response->body(json_encode($this->output));
					break;
				default:
					$this->response->body($this->output);
			}

		}
		parent::after();
	}

}
