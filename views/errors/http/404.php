<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 2:17 AM
 *
 */

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta chartset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo Website::get('site_name', '{config::site_name}'); ?></title>

	<?php Masher::instance('top_head')->add_css('3.0.0/bootstrap.min.css'); ?>
	<?php Masher::instance('top_head')->add_css('/template/default/css/style.css'); ?>
	<?php echo Masher::instance('top_head')->mark_css(); ?>
</head>

<body>
<?php
echo empty($template['header']) ? '{template.header}' : View::factory($template['header'])->render();
?>

<h1>BIG 404</h1>

<?php
echo empty($template['footer']) ? '{template.footer}' : View::factory($template['footer'])->render();
?>

<?php Masher::instance('bottom_body')->add_js('jquery-2.0.3.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('3.0.0/bootstrap.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-sanitize-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('underscore-1.5.2.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/common.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/app.js'); ?>
<?php echo Masher::instance('bottom_body')->mark_js(); ?>

</body>
</html>