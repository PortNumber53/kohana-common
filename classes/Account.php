<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Account
 */
class Account extends Abstracted
{
    const REMOVE_SENSITIVE = 'REMOVE_SENSITIVE';
    const STORAGE_COOKIE_ONLY = 'cookie_only';

    protected static $data = array();

    public static function factory()
    {
        $obj = new self();
        $obj::$data = array();

        return $obj;
    }

    public static function isLoggedIn()
    {
        $cookie_data = Cookie::get('account');
        $array_data = json_decode($cookie_data, true);
        return !empty($cookie_data) && ($array_data['profile'] !== 'guest');
    }

    public static function isGuestUser()
    {
        $cookie_data = Cookie::get('account');
        $array_data = json_decode($cookie_data, true);
        return !empty($cookie_data) && $array_data['profile'] === 'guest';
    }

    public static function logged_in()
    {
        $cookie_data = json_decode(Cookie::get('account'), true);
        $oAccount = new Model_Account();
        return $oAccount->get_by_id($cookie_data['accountid']);
    }

    public static function logout()
    {
        Cookie::delete('account');
        Auth::instance()->logout(true, true);
        return true;
    }

    public static function profile($_id = '', $options = array())
    {
        $cookie = json_decode(Cookie::get('account'), true);
        $accountData = Model_Account::getAccountByUsername($cookie['username']);

        if (empty($options[self::REMOVE_SENSITIVE])) {
            $options[self::REMOVE_SENSITIVE] = true;
        }

        if (!empty($options)) {
            if (in_array(self::REMOVE_SENSITIVE, $options)) {
                unset($accountData['password'], $accountData['hash']);
            }
        }

        return $accountData;
    }


    public static function get_author($_id)
    {
        return self::$sample_accounts[$_id];
    }


    public static function signup(&$data, &$error)
    {
        //print_r($data);
        //$data['_id'] = '/' . DOMAINNAME . '/' . $data['email'] . '/';
        //$account = new Model_Account();

        //$data['username'] = $data['email'];
        if (!empty($data['password'])) {
            $data['password'] = md5(Cookie::$salt . $data['password']);
        } else {
            $error = array(
                'error' => 255,
                'message' => __('Password is required'),
            );
            return false;
        }
        if (empty($data['username'])) {
            $error = array(
                'error' => 255,
                'message' => __('Username is required'),
            );
            return false;
        }

        if (empty($data['display_name'])) {
            $error = array(
                'error' => 255,
                'message' => __('Display Name is required'),
            );
            return false;
        }

        if ($exists = Model_Account::getAccountByUsername($data['username'])) {
            $error = array(
                'error' => 255,
                'message' => __('Account exists'),
            );
            return false;
        } else {
            //Create account
            $result = Model_Account::saveRow($data, $error);

            //Force a login
            if ($result) {
                //Only store minimal information in the cookie
                $data_cookie = array(
                    '_id' => $result['_id'],
                    'accountid' => $result['_id'],
                    'display_name' => $data['display_name'],
                    'username' => $data['username'],
                    'profile' => $data['profile'],
                );
                Cookie::set('account', json_encode($data_cookie));
            }
            return $result;
        }
    }

    public static function getLoggedAccount()
    {
        $cookie = json_decode(Cookie::get('account'), true);

        if (null === $cookie) {
            $cookie = static::createGuest();
        }

        return $cookie;
    }

    public static function createGuest()
    {
        $username = 'guest_' . str_replace('.', '', microtime(true) . mt_rand(10000, 99999));
        $data = array(
            '_id' => '/' . DOMAINNAME . '/' . $username,
            'accountid' => -1,
            'profile' => 'guest',
            'username' => $username,
            'display_name' => $username,
            'storage' => Account::STORAGE_COOKIE_ONLY,
        );
        //Force a login
        Cookie::set('account', json_encode($data));

        return $data;
    }

    public static function update(&$data, &$error)
    {
        $data['_id'] = '/' . DOMAINNAME . '/' . $data['username'] . '/';
        $account = new Model_Account();

        if (!empty($data['password'])) {
            $data['password'] = md5(Cookie::$salt . $data['password']);
        }

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
        $data['_id'] = '/' . DOMAINNAME . '/' . $data['email'] . '/';
        $account = new Model_Account();

        if (!$exists = self::profile($data['_id'])) {
            $error = array(
                'error' => 404,
                'message' => __('Account does not exist'),
            );
            return false;
        } else {
            //Add hash to account
            $exists['hash'] = md5(Cookie::$salt . '123mudar');
            $reset_url = URL::site(Route::get('account-actions')->uri(array('action' => 'reset',)), true);
            mail($data['email'], 'Password reset link', $reset_url . '?hash=' . $exists['hash']);
            $account->save($exists, $error);
            return true;
        }
    }

    public static function login($data, &$error)
    {
        $accountData = Model_Account::getAccountByUsername($data['email']);
        $error = array(
            'code' => 0,
        );
        if ($accountData) {
            if (!empty($data['hash']) && ($data['hash'] == $accountData['hash'])) {
                //Forced login
                $data_cookie = array(
                    '_id' => $accountData['_id'],
                    'profile' => $accountData['profile'],
                    'username' => $accountData['username'],
                    'email' => $accountData['email'],
                    'name' => $accountData['name'],
                    'object_id' => $accountData['object_id'],
                );
                $error = false;
                Cookie::$expiration = 604800;
                Cookie::set('account', json_encode($data_cookie));
                return true;
            } else {
                $md5 = md5(Cookie::$salt . $data['password']);
                //echo "$md5\n";
                if ($md5 == $accountData['password']) {
                    //Only store minimal information in the cookie
                    $data_cookie = array(
                        'id' => $accountData['accountid'],
                        'profile' => $accountData['profile'],
                        'username' => $accountData['username'],
                        'display_name' => $accountData['display_name'],
                    );
                    $expiration = null;
                    if (!empty($data['remember_me']) && $data['remember_me']) {
                        Cookie::$expiration = 604800;
                        $error = false;
                    }
                    Cookie::set('account', json_encode($data_cookie));
                    return true;
                } else {
                    $error = array(
                        'code' => 403,
                        'message' => 'Bad credentials',
                    );
                    return false;
                }
            }
        } else {
            $error = array(
                'code' => 404,
                'message' => 'Account not found',
            );
        }
        Cookie::delete('account');
        return false;
    }

}
