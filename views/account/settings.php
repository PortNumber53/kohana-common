<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 4:18 AM
 *
 */

?><h1>Settings</h1>

<form name="settings" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

    Key: <input type="text" name="key" id="key" size="40"
                value="<?php echo empty($item_data['key']) ? '' : htmlentities($item_data['key']); ?>">
    <br/>
    Value: <textarea name="value" id="value" cols="100"
                     rows="10"><?php echo empty($item_data['value']) ? '' : htmlentities($item_data['value']); ?></textarea>
    <br/>
    <br/>
    <button id="btnaction_new" class="form_submit button blue flat" class="form_submit button blue flat"> New Option
    </button>
    <button id="btnaction_update" class="form_submit button blue flat" class="form_submit button blue flat"> Update
        Settings
    </button>

</form>


<ul>
    <?php
    if (is_array($data['data'])) {
        foreach ($data['data'] as $key => &$item) {
            ?>
            <li>
                <a href="<?php echo URL::site(Route::get('account-actions')->uri(array('action' => 'settings', 'request' => $key,)), TRUE); ?>"><?php echo $key; ?></a>
            </li>
            <?php
        }
    }
    ?>
</ul>

<script>
    $(document).ready(function () {
        $("#btnaction_new").on('click', function () {
            document.location = "<?php echo URL::Site(route::get('account-actions')->uri(array('action' => 'settings',)), TRUE); ?>";
        })
    })
</script>
