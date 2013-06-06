<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 4:18 AM
 *
 */

?><h1>Reset</h1>


<form name="reset" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

	email: <input type="text" name="email" id="email" size="40">
	<br />
	<br />
	<button name="btnaction" id="btnaction_reset" class="form_submit button blue flat"> Reset </button>

	<br />
	<br />
	<a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'signup')), TRUE); ?>"><?php echo __("Don't have an account? Sign up now!"); ?></a>
	<br />

</form>