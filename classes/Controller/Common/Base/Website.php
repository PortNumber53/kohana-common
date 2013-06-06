<?php
/**
 * Date: 5/14/13
 * Time: 12:58 PM
 */

class Controller_Common_Base_Website extends Controller_Template
{
	public $template = 'template/default/frontend';
	public $auth_required = FALSE;
	public $auth_actions = array();

	public $output = null;


	public function __construct(Request $request, Response $response)
	{
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
		parent::before();
	}

	public function after()
	{
		parent::after();
	}

}