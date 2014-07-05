<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:12 AM
 *
 */

class Controller_Common_Core_Account extends Controller_Common_Core_Website
{
	public $auth_actions = array('profile', 'settings');

	public function action_login()
	{
		if ($post = $this->request->post())
		{
			$error = FALSE;
			if (Account::login($post, $error))
			{
				$this->redirect(URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE));
			}
		}

		$main = 'account/login';
		View::bind_global('main', $main);
	}

	public function action_logout()
	{
		Account::logout();

		$main = 'account/logout';
		View::bind_global('main', $main);
	}

	public function action_signup()
	{
		$data = Account::factory()->profile();
		if ( ! empty($data))
		{
			$this->redirect(URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE));
		}

		if ($post = $this->request->post())
		{
			if ($post['password1'] == $post['password2'])
			{
				$post['password'] = $post['password1'];
				unset($post['password1'], $post['password2']);
			}
			if ($result = Account::signup($post, $error))
			{
				$this->redirect(URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE));
			}
		}

		$main = 'account/signup';
		View::bind_global('main', $main);
	}

	public function action_ajax_signup()
	{
		$this->output = array(
			'posted' => $_POST,
		);
		$error = FALSE;

		$signup_data = array(
			'profile'   => 'user',
			'email'     => $_POST['email'],
			'password1' => $_POST['password1'],
			'password2' => $_POST['password2'],
			'remember_me' => empty($_POST['remember_me']) ? FALSE : $_POST['remember_me'],
		);
		$result = Account::signup($signup_data, $error);

		if ($error === FALSE)
		{
			$this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
		}

		$this->output['error'] = $error;
		$this->output['output'] = $result;
	}

	public function action_ajax_login()
	{
		$this->output = array(
			'posted' => $_POST,
		);
		$error = FALSE;
		$data = array(
			'email'       => $_POST['email'],
			'password'    => empty($_POST['password']) ? '' : $_POST['password'],
			'remember_me' => empty($_POST['remember_me']) ? FALSE : $_POST['remember_me'] == '1',
			'hash'        => empty($_POST['hash']) ? '' : $_POST['hash'],
		);
		if ($result = Account::login($data, $error))
		{
			$this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
		}
		$this->output['error'] = $error;
		if (! empty($error) && is_array($error) && ($error['code']) > 0)
		{
			$this->response->status( (int) $error['code']);
		}
		$this->output['output'] = $result;
	}

	public function action_ajax_reset()
	{
		$this->output = array(
			'posted' => $_POST,
		);
		$error = FALSE;
		$data = array(
			'email'       => $_POST['email'],
		);
		if ($result = Account::reset($data, $error))
		{

		}
		//$this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
		$this->output['error'] = $error;
		$this->response->status( empty($error['error']) ? 200 : $error['error']);
		$this->output['output'] = $result;
	}


	public function action_profile()
	{
		if ($post = $this->request->post())
		{
			if ($result = Account::update($post, $error))
			{
				$this->redirect(URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE));
			}
		}
		$data = Account::factory()->profile();

		View::bind_global('data', $data);
		$main = 'account/profile';
		View::bind_global('main', $main);
	}

	public function action_ajax_profile()
	{
		$this->output = array(
			'posted' => $_POST,
		);
		$error = FALSE;

		$logged_account_data = Account::logged_in();
		$data = array(
			'username' => $logged_account_data['username'],
			'email' => $_POST['email'],
		);
		if ( ! empty($_POST['name']))
		{
			$data['name'] = $_POST['name'];
		}
		if (empty($_POST['profile_avatar']))
		{
			unset($_POST['profile_avatar']);
		}
		if ( ! empty($_POST['password1']) && ! empty($_POST['password2']))
		{
			$data['password'] = $_POST['password1'];
		}
		$data = array_merge($data, $_POST);
		$result = Account::update($data, $error);

		if ($error === FALSE)
		{
			$this->output['message'] = __('Profile updated successfully');
			$this->output['dismiss_timer'] = 2;
			$this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
		}

		$this->output['error'] = $error;
		$this->output['output'] = $result;
	}

	public function action_reset()
	{
		if ($post = $this->request->post())
		{
			if (Account::reset($post, $error))
			{
				$this->redirect(URL::Site(Route::get('default')->uri(array()), TRUE));
			}
		}
		if (! empty($_GET['hash']))
		{
			//Force authentication and redirect user to profile page?
			$hash = htmlentities($_GET['hash']);
			View::bind_global('hash', $hash);
		}

		$main = 'account/reset';
		View::bind_global('main', $main);
	}

	public function action_forgot()
	{
		$main = 'account/forgot';
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
				$this->redirect(URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE));
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
		$main = 'account/settings';
		View::bind_global('main', $main);
	}

}
