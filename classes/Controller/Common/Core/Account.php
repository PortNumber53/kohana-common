<?php defined('SYSPATH') or die('No direct script access.');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Controller_Common_Core_Account
 */
class Controller_Common_Core_Account extends Controller_Website
{
    public $auth_actions = array('profile', 'settings');

    public function action_login()
    {
        $this->page_title = 'Login';
        if ($post = $this->request->post()) {
            $error = false;
            if (Account::login($post, $error)) {
                $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
            }
        }

        $main = 'account/login';
        View::bind_global('main', $main);
    }

    public function action_logout()
    {
        $this->page_title = 'Logout';
        Account::logout();

        $main = 'account/logout';
        View::bind_global('main', $main);
    }

    public function action_signup()
    {
        $this->page_title = 'Sign Up';
        $data = Account::factory()->profile();
        if (!empty($data) && $data['profile'] !== 'guest') {
            $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
        }

        if ($post = $this->request->post()) {
            if ($post['password1'] == $post['password2']) {
                $post['password'] = $post['password1'];
                unset($post['password1'], $post['password2']);
            }
            if ($result = Account::signup($post, $error)) {
                $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
            }
        }
        View::bind_global('data', $data);

        $main = 'account/signup';
        View::bind_global('main', $main);
    }

    public function action_ajax_signup()
    {
        $error = false;

        $signup_data = array(
            'profile' => 'user',
            'username' => $name = filter_var(Arr::path($this->json, 'username', $this->request->post('username')),
                FILTER_SANITIZE_STRING),
            'password' => $name = filter_var(Arr::path($this->json, 'password', $this->request->post('password')),
                FILTER_SANITIZE_STRING),
            'display_name' => $name = filter_var(Arr::path($this->json, 'display_name',
                $this->request->post('display_name')), FILTER_SANITIZE_STRING),
        );
        $result = Account::signup($signup_data, $error);

        if ($error === false) {
            $this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)),
                true);
        } else {
            $this->response->headers('content-type', 'application/json');
        }

        $this->output['error'] = $error;
        $this->output['output'] = $result;
    }

    public function action_ajax_login()
    {
        $error = false;
        $data = array(
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_STRING),
            'password' => empty($_POST['password']) ? '' : filter_var($_POST['password'], FILTER_SANITIZE_STRING),
            'remember_me' => empty($_POST['remember_me']) ? false : $_POST['remember_me'] == '1',
            'hash' => empty($_POST['hash']) ? '' : filter_var($_POST['hash'], FILTER_SANITIZE_STRING),
        );
        if ($result = Account::login($data, $error)) {
            $this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)),
                true);
        }
        $this->output['error'] = $error;
        if (!empty($error) && is_array($error) && ($error['code']) > 0) {
            $this->response->status((int)$error['code']);
        }
        $this->output['output'] = $result;
    }

    public function action_reset()
    {
        $this->page_title = 'Reset Password';
        if (!empty($_GET['hash'])) {
            //Force authentication and redirect user to profile page?
            $hash = htmlentities($_GET['hash']);
            View::bind_global('hash', $hash);
            $result = Account::forceLogIn(array(
                'hash' => $hash,
            ));
            if ($result) {
                $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
            }
        }
        $this->redirect(URL::Site(Route::get('default')->uri(array()), true));
    }

    public function action_ajax_reset()
    {
        $error = false;
        $data = array(
            'email' => $_POST['email'],
        );
        if ($result = Account::reset($data, $error)) {

        }
        //$this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE);
        $this->output['error'] = $error;
        $this->response->status(empty($error['error']) ? 200 : $error['error']);
        $this->output['output'] = $result;
    }


    public function action_profile()
    {
        $this->page_title = 'Profile';
        if ($post = $this->request->post()) {
            if ($result = Account::update($post, $error)) {
                $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
            }
        }
        $data = Account::factory()->profile();

        View::bind_global('account_data', $data);
        $main = 'account/profile';
        View::bind_global('main', $main);
    }

    public function action_ajax_profile()
    {
        $error = false;

        $logged_account_data = Account::factory()->profile();
        $data = array(
            'accountid' => $logged_account_data['accountid'],
            'username' => filter_var($_POST['username'], FILTER_SANITIZE_STRING),
        );
        if (!empty($_POST['display_name'])) {
            $data['display_name'] = filter_var($_POST['display_name'], FILTER_SANITIZE_STRING);
        }
        if (empty($_POST['profile_avatar'])) {
            unset($_POST['profile_avatar']);
        }
        if (!empty($_POST['password1']) && !empty($_POST['password2'])) {
            $data['password'] = filter_var($_POST['password1'], FILTER_SANITIZE_STRING);
        }
        //$data = array_merge($data, $_POST);
        $result = Account::update($data, $error);

        if ($error === false) {
            $this->output['message'] = __('Profile updated successfully');
            $this->output['dismiss_timer'] = 2;
            $this->output['redirect_url'] = URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)),
                true);
        }

        $this->output['error'] = $error;
        $this->output['output'] = $result;
    }

    public function action_forgot()
    {
        $this->page_title = 'Forgot Password';
        $main = 'account/forgot';
        View::bind_global('main', $main);
    }

    public function action_ajax_forgot()
    {
        $queue_name = Environment::level() . '-' . 'forgot-message';
        $this->output['_DEBUG'] = $_POST;

        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

        $model_account = new Model_Account();
        $account_data = $model_account->getAccountByUsername($email);

        if ($account_data) {
            $hash = md5($account_data['accountid'] . Arr::path(self::$settings, 'website.cookie_salt', time()));
            $account_data['hash'] = $hash;
            $options = array();
            $model_account->save($account_data, $options);
            $this->output['account'] = $account_data;
            $this->output['hash'] = $hash;

            $email_data = array(
                'name' => $account_data['display_name'],
                'email' => $email,
                'template' => 'forgot-password',
                'hash' => $hash,
            );

            $queue_settings = Arr::path(self::$settings, 'rabbitmq');

            $connection = new AMQPStreamConnection($queue_settings['host'], $queue_settings['port'],
                $queue_settings['user'], $queue_settings['password']);
            $channel = $connection->channel();
            $channel->queue_declare($queue_name, false, true, false, false);


            $msg = new AMQPMessage(json_encode($email_data), array('delivery_mode' => 2));

            $result = $channel->basic_publish($msg, '', $queue_name);

            $channel->close();
            $connection->close();

            $this->output['errorCode'] = 0;
        } else {
            $this->output['errorCode'] = 404;
        }
    }

    public function action_settings()
    {
        $this->page_title = 'Account Settings';
        $request = $this->request->param('request');

        $user_data = Account::factory()->profile();
        $account_id = $user_data['pk_id'];
        $settings_id = $user_data['_id'] . '/settings';
        $settings_data = Settings::get($account_id);

        if ($post_data = $this->request->post()) {
            $validated = array(
                $post_data['key'] => $post_data['value'],
            );

            $post_data['_id'] = $settings_id;
            $post_data['account_id'] = $user_data['pk_id'];
            if (empty($settings_data['data'])) {
                $settings_data['data'] = array();
            }

            $settings_data['data'] = array_merge($settings_data['data'], $validated);
            if ($result = Settings::update($settings_data, $error)) {
                $this->redirect(URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)), true));
            }
        }

        if (!empty($request)) {
            foreach ($settings_data['data'] as $key => &$item) {
                if ($request == $key) {
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
