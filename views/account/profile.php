<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:13 AM
 *
 */

?>
<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE); ?>">
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">Email</label>
		<div class="col-lg-10">
			<input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email" value="<?php echo $data['email']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputName" class="col-lg-2 control-label">Name</label>
		<div class="col-lg-10">
			<input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="<?php echo $data['name']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputPassword1" class="col-lg-2 control-label">Password</label>
		<div class="col-lg-10">
			<input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password1">
		</div>
	</div>
	<div class="form-group">
		<label for="inputPassword2" class="col-lg-2 control-label">Password (Confirm)</label>
		<div class="col-lg-10">
			<input type="password" class="form-control" id="inputPassword2" placeholder="Password" name="password2">
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Update My Profile</button>
		</div>
	</div>
	</div>
</form>


