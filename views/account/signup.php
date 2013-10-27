<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 3:37 AM
 *
 */

?><h1>Sign Up</h1>

<form name="signup" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

	Username: <input type="text" name="username" id="username" size="40">
	<br />
	Password: <input type="password" name="password1" id="password1" size="40">
	<br />
	Password (confirm): <input type="password" name="password2" id="password2" size="40">
	<br />
	Name: <input type="text" name="name" id="name" size="40">
	<br />
	email: <input type="text" name="email" id="email" size="40">
	<br />
	Gender: <input type="text" name="gender" id="gender" size="1" value="m">
	<br />
	Date of Birth: <input type="text" name="date_of_birth" id="date_of_birth" size="10">
	<br />
	<br />
	<button name="btnaction" id="btnaction_signup" class="form_submit button blue flat"> Sign Up </button>

</form>
