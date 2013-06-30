<?php
/**
 * Date: 5/14/13
 * Time: 12:58 PM
 */

class Controller_Common_Base_Website extends Controller_Template
{
	public $template_name = '';
	public $template_file = 'frontend';

	public $auth_required = FALSE;
	public $auth_actions = array();

	public $output = null;

	public static $settings = array();

	public function __construct(Request $request, Response $response)
	{
		$session = Session::instance();

		self::$settings = Kohana::$config->load('website');
		Cookie::$salt = Arr::path(self::$settings, 'cookie_salt');
		View::set_global('debug', Arr::path(self::$settings, 'debug', FALSE));


		parent::__construct($request, $response);
		//If a user is not logged in and authentication is required:
		if ($this->auth_required && ! Auth::instance()->logged_in())
			$this->redirect('/login?url='.URL::site(Request::current()->uri()));

		if (in_array($this->request->action(), $this->auth_actions))
		{
			if ( ! Account::factory()->is_logged())
			{
				echo $this->request->action() . ' requires Authentication!';
				$this->redirect('/login?url='.URL::site(Request::current()->uri()));
			}
			$this->template_file = 'backend';
		};

		if ($this->request->is_ajax())
		{
			$this->auto_render = false;
			$this->request->action('ajax_'.$this->request->action());
		}
	}


	public function before()
	{
		if (empty($this->template_name))
		{
			$this->template_name = Website::get('template.selected', 'default');
			//echo 'template_name'.$this->template_name;
		}
		Website::set_template($this->template_name);

		if (empty($this->template_file))
		{
			$this->template_file = 'frontend';
		}
		Website::set_file($this->template_file);
		$new_template = 'template/' . $this->template_name . '/' . $this->template_file;
		if (! Kohana::find_file('views', $new_template))
		{
			$new_template = 'template/default/' . $this->template_file;
		}
		$this->template = $new_template;

		parent::before();

		if ($this->auto_render)
		{
			View::set_global('param', Request::current()->param());
			View::$template_name = $this->template_name;
			View::set_global('title', '');
			View::set_global('content', '');
			View::set_global('language', Kohana::$config->load('contentus.default_language'));
			View::set_global('body_class', '');
			View::set_global('meta', array());

			View::set_global('styles', array());
			View::set_global('scripts', array());
			View::set_global('breadbrumbs', array());

		}
	}

	public function after()
	{
		if ($this->auto_render)
		{
			View::bind_global('user', $this->user);
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
			$this->response->headers('Content-Encoding', 'UTF-8');
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