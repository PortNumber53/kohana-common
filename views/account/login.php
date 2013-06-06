<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:13 AM
 *
 */

?><h1>Login</h1>


<form name="login" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

	Username: <input type="text" name="username" id="username" size="40" />
	<br />
	Password: <input type="password" name="password" id="password" size="40" />
	<br />
	<br />
	<button name="btnaction" id="btnaction_login" class="form_submit button blue flat"> Login </button>

	<br />
	<br />
	<br />
	<a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'reset')), TRUE); ?>"><?php echo __("Forgot your password?"); ?></a>
	<br />
	<a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'signup')), TRUE); ?>"><?php echo __("Don't have an account? Sign up now!"); ?></a>
	<br />

</form>