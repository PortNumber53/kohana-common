<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 7:55 PM
 * Something meaningful about this file
 *
 */

?>
<li>
    <a href="#" class="dropdown-toogle" data-toggle="dropdown">Your Account<b class="caret"></b></a>

    <div class="dropdown-menu" style="width:400px">

        <form class="form-horizontal json-form" role="form" method="post"
              action="<?php echo URL::Site(Route::get('account-actions')->uri(array('action' => 'login',)),
                      true) . '/'; ?>">
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Email</label>

                <div class="col-lg-10">
                    <input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword1" class="col-lg-2 control-label">Password</label>

                <div class="col-lg-10">
                    <input type="password" class="form-control" id="inputPassword1" placeholder="Password"
                           name="password">
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
                    <button type="submit" name="action" class="btn btn-default" value="signin">Sign in</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <div class="checkbox">
                        <label>
                            <a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action' => 'signup',)),
                                true); ?>"> Sign up here</a>
                        </label>
                        <label>
                            <a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action' => 'forgot',)),
                                true); ?>"> Reset password</a>
                        </label>
                    </div>
                </div>
            </div>
        </form>

    </div>
</li>
