<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:12 AM
 *
 */

class Controller_Common_Base_Account extends Controller_Common_Base_Website
{
	public $auth_actions = array('profile', 'settings');

	public function action_profile()
	{
		if ($post = $this->request->post())
		{
			if ($result = Account::update($post, $error))
			{
				$this->redirect('/profile');
			}
			//var_dump($error);
		}

		$data = Account::factory()->profile();

		View::bind_global('data', $data);
		$main = View::factory('account/profile')->render();
		View::bind_global('main', $main);
	}

	public function action_login()
	{
		if ($post = $this->request->post())
		{
			if (Account::login($post['username'], $post['password']))
			{
				$this->redirect('/profile');
			}
		}

		$main = View::factory('account/login')->render();
		View::bind_global('main', $main);
	}

	public function action_logout()
	{
		Account::logout();

		$main = View::factory('account/logout')->render();
		View::bind_global('main', $main);
	}

	public function action_signup()
	{
		if ($post = $this->request->post())
		{
			if ($post['password1'] == $post['password2'])
			{
				$post['password'] = $post['password1'];
				unset($post['password1'], $post['password2']);
			}
			if ($result = Account::signup($post, $error))
			{
				$this->redirect('/profile');
			}
			//var_dump($error);
		}

		$main = View::factory('account/signup')->render();
		View::bind_global('main', $main);
	}

	public function action_reset()
	{
		if ($post = $this->request->post())
		{
			if (Account::reset($post, $error))
			{
				//$this->redirect('/');
			}
		}

		$main = View::factory('account/reset')->render();
		View::bind_global('main', $main);
	}

	public function action_forgot()
	{
		$main = View::factory('account/forgot')->render();
		View::bind_global('main', $main);
	}

	public function action_settings()
	{
		$request = $this->request->param('request');

		$user_data = Account::factory()->profile();
		$account_id = $user_data['pk_id'];
		$settings_id = $user_data['_id'] . '/settings';
		$settings_data = Settings::get($account_id);

		if ($post_data = $this->request->post())
		{
			$validated = array(
				$post_data['key'] => $post_data['value'],
			);

			$post_data['_id'] = $settings_id;
			$post_data['account_id'] = $user_data['pk_id'];
			if (empty($settings_data['data']))
			{
				$settings_data['data'] = array();
			}

			$settings_data['data'] = array_merge($settings_data['data'], $validated);
			if ($result = Settings::update($settings_data, $error))
			{
				//$this->redirect('/profile');
			}
		}

		if ( ! empty($request))
		{
			foreach ($settings_data['data'] as $key=>&$item)
			{
				if ($request == $key)
				{
					$item_data['key'] = $key;
					$item_data['value'] = $item;
				}
			}
		}


		View::bind_global('data', $settings_data);
		View::bind_global('item_data', $item_data);
		$main = View::factory('account/settings')->render();
		View::bind_global('main', $main);
	}

}