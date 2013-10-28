<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 3:13 AM
 *
 */

?><h1>Login</h1>

<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'login', )), TRUE); ?>">
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">Email</label>
		<div class="col-lg-10">
			<input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
		</div>
	</div>
	<div class="form-group">
		<label for="inputPassword1" class="col-lg-2 control-label">Password</label>
		<div class="col-lg-10">
			<input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password">
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label title="(for a week)">
					<input type="checkbox" name="remember_me" value="1"> Remember me
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Sign in</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<div class="checkbox">
				<label>
					<a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action'=>'signup', )), TRUE); ?>"> Sign up here</a>
				</label>
			</div>
		</div>
	</div>
</form>

