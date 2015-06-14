<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 6:29 PM
 * Something meaningful about this file
 *
 */

class Product
{
	const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

	protected static $data = array();

	public static $class_name = 'Model_Product';

	public static function factory()
	{
		$obj = new self();
		$obj::$data = array();

		return $obj;
	}

	public static function get_by_id($_id)
	{
		$oProduct = new Model_Product();
		$result = $oProduct->get_by_id($_id);
		return $result;
	}

	public static function get_by_object_id($object_id)
	{
		$oProduct = new Model_Product();
		$result = $oProduct->get_by_object_id($object_id);
		return $result;
	}

	public static function get_empty_row()
	{
		$oProduct = new Model_Product();
		return $oProduct::$_columns;
	}

	public static function isLoggedIn()
	{
		$cookie_data = Cookie::get('product');
		return ! empty($cookie_data);
	}

	public static function logged_in()
	{
		$cookie_data = json_decode(Cookie::get('product'), TRUE);
		return self::get_by_id($cookie_data['_id']);
	}

	public static function logout()
	{
		Cookie::delete('product');
		return TRUE;
	}

	public static function profile($_id = '', $options = array())
	{
		$cookie = json_decode(Cookie::get('product'), TRUE);
		if (empty($_id))
		{
			$_id = '/' . DOMAINNAME . '/' . $cookie['email'];
		}
		$product = new Model_Product();
		$data = $product->get_by_id($_id);

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


	public static function get_author($_id)
	{
		return self::$sample_accounts[$_id];
	}


	public static function signup(&$data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$product = new Model_Product();

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
			$result = $product->save($data, $error);
			//Force a login
			if ($result)
			{
				//Only store minimal information in the cookie
				$data_cookie = array(
					'_id'      => $data['_id'],
					'username' => $data['username'],
					'email'    => $data['email'],
				);
				Cookie::set('product', json_encode($data_cookie));
			}
		}
	}

	public static function update(&$data, &$error)
	{
		if ( empty($data['_id']) )
		{
			$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		}
		$product = new Model_Product();
		$result = $product->save($data, $error);
		return $result;
	}

	public static function reset($data, &$error)
	{
		$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
		$product = new Model_Product();

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
			$exists['hash'] = md5('123mudar');
			$product->save($exists, $error);
			return TRUE;
		}
	}

	public static function login($data, &$error)
	{
		// TODO: Implement _login() method.
		$_id = '/' . DOMAINNAME . '/' . $data['username'];
		$product = new Model_Product();
		$product_row = $product->get_by_id($_id);

		if ($product_row)
		{
			if (md5(Cookie::$salt . $data['password']) == $product_row['password'])
			{
				//Only store minimal information in the cookie
				$data_cookie = array(
					'_id'      => $product_row['_id'],
					'username' => $product_row['username'],
					'email'    => $product_row['email'],
					'name'     => $product_row['name'],
				);
				if ($data['remember_me'])
				{
					Cookie::set('product', json_encode($data_cookie));
					$error = FALSE;
				}
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
		//Cookie::delete('product');
		return FALSE;
	}


	public static function filter($filter=array(), $sort=array(), $limit=array())
	{
		$oProduct = new Model_Product();
		$result = $oProduct->filter($filter, $sort, $limit);

		return $result;
	}


	public static function delete_by_object_id($object_id, &$error)
	{
		$oProduct = new Model_Product();
		$result = $oProduct->delete_by_object_id($object_id, $error);

		return $result;
	}

}
