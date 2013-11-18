<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:19 AM
 *
 */

class Account extends Abstracted
{
	const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

	protected static $data = array();

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		return $obj;
	}


	static public function is_logged_in()
	{
		$cookie_data = Cookie::get('account');
		return ! empty($cookie_data);
	}

	static public function logged_in()
	{
		$cookie_data = json_decode(Cookie::get('account'), TRUE);
		$oAccount = new Model_Account();
		return $oAccount->get_by_id($cookie_data['_id']);
	}

	static public function logout()
	{
		Cookie::delete('account');
		return TRUE;
	}

	static public function profile($_id = '', $options = array())
	{
		$cookie = json_decode(Cookie::get('account'), TRUE);
		if (empty($_id))
		{
			$_id = '/' . DOMAINNAME . '/' . $cookie['email'];
		}
		$account = new Model_Account();
		$data = $account->get_by_id($_id);

		if (empty($options[self::REMOVE_SENSITIVE]))
		{
			$options[self::REMOVE_SENSITIVE] = TRUE;
		}

		if ( ! empty($options))
		{
			if (in_array(self::REMOVE_SENSITIVE, $options))
			{
				unset($data['password'], $data['hash']);
			}
		}

		return $data;
	}


	static public function get_author($_id)
	{
		return self::$sample_accounts[$_id];
	}


	static public function signup(&$data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$account = new Model_Account();

		$data['username'] = $data['email'];
		if ( ($data['password1'] === $data['password2']) && ( ! empty($data['password1'])) )
		{
			$data['password'] = md5(Cookie::$salt . $data['password1']);
		}
		else
		{
			$error = array(
				'error' =>  255,
				'message' => __('Bad password'),
			);
			return FALSE;
		}


		if ($exists = self::profile($data['_id']))
		{
			$error = array(
				'error' =>  255,
				'message' => __('Account exists'),
			);
			return FALSE;
		}
		else
		{
			//Create account
			$result = $account->save($data, $error);
			//Force a login
			if ($result)
			{
				//Only store minimal information in the cookie
				$data_cookie = array(
					'_id'      => $data['_id'],
					'username' => $data['username'],
					'email'    => $data['email'],
				);
				Cookie::set('account', json_encode($data_cookie));
			}
		}
	}

	static public function update(&$data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$account = new Model_Account();

		if (! $exists = self::profile($data['_id']))
		{
			$error = array(
				'error' =>  255,
				'message' => __('Account does not exist'),
			);
			return FALSE;
		}
		else
		{
			//Update account
			$account->save($data, $error);
			return TRUE;
		}
	}

	static public function reset($data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$account = new Model_Account();

		if (! $exists = self::profile($data['_id']))
		{
			$error = array(
				'error' =>  255,
				'message' => __('Account does not exist'),
			);
			return FALSE;
		}
		else
		{
			//Add hash to account
			//var_dump($exists);
			$exists['hash'] = md5('123mudar');
			$account->save($exists, $error);
			return TRUE;
		}
	}

	static public function login($data, &$error)
	{
		// TODO: Implement _login() method.
		$_id = '/' . DOMAINNAME . '/' . $data['email'];
		$account = new Model_Account();
		$account_row = $account->get_by_id($_id);

		if ($account_row)
		{
			$md5 = md5(Cookie::$salt . $data['password']);
			if ($md5 == $account_row['password'])
			{
				//Only store minimal information in the cookie
				$data_cookie = array(
					'_id'      => $account_row['_id'],
					'username' => $account_row['username'],
					'email'    => $account_row['email'],
					'name'     => $account_row['name'],
				);
				$expiration = NULL;
				if ( ! empty($data['remember_me']) && $data['remember_me'])
				{
					$expiration = 86400;
					Cookie::$expiration = 604800;
					$error = FALSE;
				}
				//echo "SET COOKIE!";die();
				Cookie::set('account', json_encode($data_cookie));
				return TRUE;
			}
			else
			{
				$error = array(
					'error'   => 403,
					'message' => 'Bad credentials',
				);
				return FALSE;
			}
		}
		else
		{
			$error = array(
				'error'   => 404,
				'message' => 'Account not found',
			);
		}
		//Cookie::delete('account');
		return FALSE;
	}

}
