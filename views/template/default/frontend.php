<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/6/13
 * Time: 1:59 AM
 * Something meaningful about this file
 *
 */

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        echo (isset($page_title) && $page_title !== '') ? "$page_title - " : '';
        echo Arr::path($site_settings, 'site_name', '{config::site_name}');
        ?></title>
    <link rel="canonical"
          href="<?php echo empty($canonical_url) ? URL::site('/', TRUE) : str_replace('/.html', '/', $canonical_url); ?>"/>

    <?php Masher::instance('top_head')->add_css('3.0.0/bootstrap.min.css'); ?>
    <?php Masher::instance('top_head')->add_css('/template/' . Arr::path($site_settings, 'template.selected', 'default') . '/css/bootstrap.css'); ?>
    <?php Masher::instance('top_head')->add_css('/template/' . Arr::path($site_settings, 'template.selected', 'default') . '/css/style.css'); ?>
    <?php echo Masher::instance('top_head')->mark_css(); ?>

    <?php Masher::instance('top_head')->add_js('jquery-2.0.3.min.js'); ?>
    <?php echo Masher::instance('top_head')->mark_js(); ?>

    <?php
    if (isset(View::$_global_data['og:tags'])) {
        foreach (View::$_global_data['og:tags'] as $tag => $value) {
            if (!empty($value)) {
                ?>
                <meta property="<?php echo $tag; ?>" content="<?php echo $value; ?>" /><?php
            }
        }
    }
    ?>
</head>

<body ng-app="app">
<?php
echo empty($template['facebook']) ? '{template.facebook}' : View::factory($template['facebook'])->render();
echo empty($template['header']) ? '{template.header}' : View::factory($template['header'])->render();
?>

<div id="message" ng-show="message" class="hidden">{{message}}</div>
<div id="view" ng-view></div>

<div class="container">
    <?php
    echo empty($main) ? '{no $main content}' : View::factory($main)->render();
    ?>
</div>


<?php
echo empty($template['footer']) ? '{template.footer}' : View::factory($template['footer'])->render();
//echo View::factory('modules/akm2_gravity_points_code_pen')->render();
?>

<?php Masher::instance('bottom_body')->add_js('jquery.form.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('3.0.0/bootstrap.min.js'); ?>
<?php //Masher::instance('bottom_body')->add_js('angular-1.1.5.min.js'); ?>
<?php //Masher::instance('bottom_body')->add_js('angular-sanitize-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('underscore-1.5.2.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.cookie.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.lazyload.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/jquery.filedrop.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/template.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/common.js'); ?>
<?php //Masher::instance('bottom_body')->add_js('/script/app.js'); ?>
<?php echo Masher::instance('bottom_body')->mark_js(); ?>

<?php //echo View::factory('google/analytics/tracking')->render(); ?>
<?php echo View::factory('modules/social/googleplus_common')->render(); ?>
<?php echo View::factory('modules/social/stumbleupon_common')->render(); ?>
<?php echo View::factory('modules/social/pinterest_common')->render(); ?>

</body>
</html>
