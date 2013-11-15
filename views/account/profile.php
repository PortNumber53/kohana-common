<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:13 AM
 *
 */

?>
<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE); ?>">
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<div class="thumbnail">
					<div id="dropbox" class="text-center container center-block">
						<img src="http://placehold.it/300x200" alt="...">
						<span class="message">Drop images here to upload. <br /><i>(they will only be visible to you)</i></span>
					</div>
					<div class="caption">

						<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>

					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">

			<div class="form-group">
				<label for="inputEmail1" class="col-sm-2  control-label">Email</label>
				<div class="col-sm-10">
					<input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email" value="<?php echo Arr::path($data, 'email'); ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="inputName" class="col-sm-2  control-label">Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="<?php echo Arr::path($data, 'name'); ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="inputDisplayName" class="col-sm-2  control-label">Display Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="inputDisplayName" placeholder="Name" name="display_name" value="<?php echo Arr::path($data, 'display_name'); ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="inputPassword1" class="col-sm-2  control-label">Password</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password1">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword2" class="col-sm-2  control-label">Password (Confirm)</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" id="inputPassword2" placeholder="Password" name="password2">
				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="center-block">
				<button type="submit" class="btn btn-default">Update My Profile</button>
			</div>
		</div>
	</div>

</form>


