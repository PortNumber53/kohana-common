<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 4:18 AM
 *
 */

$route = URL::Site(Route::get('account-actions')->uri(array('action' => 'reset',)), TRUE);
if (!empty($hash)) {
    $route = URL::Site(Route::get('account-actions')->uri(array('action' => 'login',)), TRUE);
}

?>
<form class="form-horizontal json-form" role="form" method="post" action="<?php echo $route; ?>">
    <input type="hidden" name="hash" value="<?php echo empty($hash) ? '' : $hash; ?>"/>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail1" class="col-md-2 control-label">Email</label>
                <div class="col-md-10">
                    <input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" name="btnaction" value="reset" class="btn btn-default">Reset</button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <div class="checkbox">
                    <label>
                        <a href="<?php echo URL::Site(Route::get('account-actions')->uri(array('action' => 'login',)), TRUE); ?>">
                            Remembered your password? Login here</a>
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>
