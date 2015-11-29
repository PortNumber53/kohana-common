<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Class Auth_Common
 */
class Auth_Common extends Auth {

    // User list
    protected $_users;

    /**
     * Constructor loads the user list into the class.
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        // Load user list
        $this->_users = Arr::get($config, 'users', array());
    }

    /**
     * Logs a user in.
     *
     * @param   string   $username  Username
     * @param   string   $password  Password
     * @param   boolean  $remember  Enable autologin (not supported)
     * @return  boolean
     */
    protected function _login($username, $password, $remember)
    {
        if (is_string($password))
        {
            // Create a hashed password
            $password = $this->hash($password);
        }

        if (isset($this->_users[$username]) AND $this->_users[$username] === $password)
        {
            // Complete the login
            return $this->complete_login($username);
        }

        // Login failed
        return FALSE;
    }

    /**
     * Forces a user to be logged in, without specifying a password.
     *
     * @param   mixed    $username  Username
     * @return  boolean
     */
    public function force_login($username)
    {
        // Complete the login
        return $this->complete_login($username);
    }

    /**
     * Get the stored password for a username.
     *
     * @param   mixed   $username  Username
     * @return  string
     */
    public function password($username)
    {
        return Arr::get($this->_users, $username, FALSE);
    }

    /**
     * Compare password with original (plain text). Works for current (logged in) user
     *
     * @param   string   $password  Password
     * @return  boolean
     */
    public function check_password($password)
    {
        $username = $this->get_user();

        if ($username === FALSE)
        {
            return FALSE;
        }

        return ($password === $this->password($username));
    }

    public function logged_in($role = NULL)
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
            'profile' => 'guest',
            'username' => $username,
            'display_name' => $username,
            'storage' => Account::STORAGE_COOKIE_ONLY,
        );
        //Force a login
        Cookie::set('account', json_encode($data));

        return $data;
    }


} // End Auth File
