<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Category
 */
class Category
{
    const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';

    protected static $data = array();


    public static function factory()
    {
        $obj = new self();
        $obj::$data = array();

        return $obj;
    }

    public static function get_by_id($_id)
    {
        $oCategory = new Model_Category();
        $result = $oCategory->get_by_id($_id);
        return $result;
    }

    public static function get_by_object_id($object_id)
    {
        $oCategory = new Model_Category();
        $result = $oCategory->get_by_object_id($object_id);
        return $result;
    }

    public static function isLoggedIn()
    {
        $cookie_data = Cookie::get('category');
        return !empty($cookie_data);
    }

    public static function logged_in()
    {
        $cookie_data = json_decode(Cookie::get('category'), true);
        return self::get_by_id($cookie_data['_id']);
    }

    public static function logout()
    {
        Cookie::delete('category');
        return true;
    }

    public static function profile($_id = '', $options = array())
    {
        $cookie = json_decode(Cookie::get('category'), true);
        if (empty($_id)) {
            $_id = '/' . DOMAINNAME . '/' . $cookie['email'];
        }
        $account = new Model_Category();
        $data = $account->get_by_id($_id);

        if (empty($options[self::REMOVE_SENSITIVE])) {
            $options[self::REMOVE_SENSITIVE] = true;
        }

        if (!empty($options)) {
            if (in_array(self::REMOVE_SENSITIVE, $options)) {
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
        $account = new Model_Category();

        $data['username'] = $data['email'];
        if (($data['password1'] === $data['password2']) && (!empty($data['password1']))) {
            $data['password'] = md5(Cookie::$salt . $data['password1']);
        } else {
            $error = array(
                'error' => 255,
                'message' => __('Bad password'),
            );
            return false;
        }


        if ($exists = self::profile($data['_id'])) {
            $error = array(
                'error' => 255,
                'message' => __('Account exists'),
            );
            return false;
        } else {
            //Create account
            $result = $account->save($data, $error);
            //Force a login
            if ($result) {
                //Only store minimal information in the cookie
                $data_cookie = array(
                    '_id' => $data['_id'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                );
                Cookie::set('category', json_encode($data_cookie));
            }
        }
    }

    public static function update(&$data, &$error)
    {
        $data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
        $account = new Model_Category();

        if (!$exists = self::profile($data['_id'])) {
            $error = array(
                'error' => 255,
                'message' => __('Account does not exist'),
            );
            return false;
        } else {
            //Update account
            $account->save($data, $error);
            return true;
        }
    }

    public static function reset($data, &$error)
    {
        $data['_id'] = '/' . DOMAINNAME . '/' . $data['email'];
        $account = new Model_Category();

        if (!$exists = self::profile($data['_id'])) {
            $error = array(
                'error' => 255,
                'message' => __('Account does not exist'),
            );
            return false;
        } else {
            //Add hash to account
            $exists['hash'] = md5('123mudar');
            $account->save($exists, $error);
            return true;
        }
    }

    public static function login($data, &$error)
    {
        // TODO: Implement _login() method.
        $_id = '/' . DOMAINNAME . '/' . $data['username'];
        $account = new Model_Category();
        $account_row = $account->get_by_id($_id);

        if ($account_row) {
            if (md5(Cookie::$salt . $data['password']) == $account_row['password']) {
                //Only store minimal information in the cookie
                $data_cookie = array(
                    '_id' => $account_row['_id'],
                    'username' => $account_row['username'],
                    'email' => $account_row['email'],
                    'name' => $account_row['name'],
                );
                if ($data['remember_me']) {
                    Cookie::set('category', json_encode($data_cookie));
                    $error = false;
                }
                return true;
            } else {
                $error = array(
                    'error' => 403,
                    'message' => 'Bad credentials',
                );
                return false;
            }
        } else {
            $error = array(
                'error' => 404,
                'message' => 'Account not found',
            );
        }
        //Cookie::delete('category');
        return false;
    }
}
