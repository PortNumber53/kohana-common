<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:13 AM
 *
 */

?><h1>Profile</h1>

<form name="profile" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

	Username: <input type="text" name="username" id="username" size="40" value="<?php echo $data['username']; ?>">
	<br />
	name: <input type="text" name="name" id="name" size="40" value="<?php echo $data['name']; ?>">
	<br />
	email: <input type="text" name="email" id="email" size="40" value="<?php echo $data['email']; ?>">
	<br />
	Date of Birth: <input type="text" name="date_of_birth" id="date_of_birth" size="40" value="<?php echo $data['date_of_birth']; ?>">
	<br />
	Gender: <input type="text" name="gender" id="gender" size="40" value="<?php echo $data['gender']; ?>">
	<br />
	<br />
	<button id="form_update" class="form_submit button blue flat"> Update </button>
</form>

