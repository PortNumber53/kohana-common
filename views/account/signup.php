<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 3:37 AM
 *
 */

?>
<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'signup', )), TRUE); ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="inputEmail1" class="col-md-2 control-label">Email</label>
				<div class="col-md-10">
					<input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword1" class="col-md-2 control-label">Password</label>
				<div class="col-md-10">
					<input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password1">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword2" class="col-md-2 control-label">Password (Confirm)</label>
				<div class="col-md-10">
					<input type="password" class="form-control" id="inputPassword2" placeholder="Password" name="password2">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="remember_me" value="1"> Remember me
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<button type="submit" class="btn btn-default">Create My Account Now</button>
				</div>
			</div>
		</div>
	</div>
</form>
