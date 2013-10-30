<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:40 AM
 *
 */

class Content
{
	protected static $data = array();

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		//$obj::$template_file = $template_file_name;
		//echo $obj::$template_file;

		//if (empty(self::$template_file))
		//{
		//	self::$template_file = 'frontend';
		//}/
		//$this->template = 'template/' . $this->template_name . '/' . $this->template_file;

		return $obj;
	}

	static function is_logged()
	{
		$logged_in = Cookie::get('account');
		return ! empty($logged_in);
	}


	static function login($username, $password, $remember_me = TRUE)
	{
		//$cookie = json_decode(Cookie::get('account'), TRUE);
		//if (empty($_id))
		//{
		$_id = '/insertcoin.dev/' . $username;
		//}
		$account = new Model_Account();
		$data = $account->get_by_id($_id);

		if ($username == $data['username'])
		{
			if ($password == $data['password'])
			{
				Cookie::set('account', json_encode($data));
				return TRUE;
			}
		}
		return FALSE;
	}

	static public function get($_id = '')
	{
		if (substr($_id, 0, 1) !== '/')
		{
			$_id = '/' . $_id;
		}
		//echo "GETTING: $_id<br>";
		$_id = '/' . DOMAINNAME . $_id;
		$content = new Model_Content();
		$data = $content->get_by_id($_id);

		return $data;
	}


	static public function get_author($_id)
	{
		return self::$sample_accounts[$_id];
	}


	static public function signup(&$data, &$error)
	{
		$data['_id'] = '/insertcoin.dev/'.$data['email'];
		$account = new Model_Account();

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
			$account->save($data, $error);
			return TRUE;
		}
	}


	static public function update(&$data, &$error)
	{
		//$data['_id'] = '/insertcoin.dev/'.$data['email'];
		$content = new Model_Content();

		$autor = Account::profile();
		$data['author_id'] = $autor['object_id'];
		//Update content
		if ($result = $content->save($data, $error))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	static public function logout()
	{
		Cookie::delete('account');
	}

	static public function filter($filter=array(), $sort=array(), $limit=array())
	{
		$oContent = new Model_Content();
		$result = $oContent->filter($filter, $sort, $limit);

		return $result;
	}
}