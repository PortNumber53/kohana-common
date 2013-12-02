<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 2:43 AM
 *
 */

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta chartset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo Website::get('site_name', '{config::site_name}'); ?></title>

	<?php Masher::instance('top_head')->add_css('3.0.0/bootstrap.min.css'); ?>
	<?php Masher::instance('top_head')->add_css('/template/default/css/bootstrap.css'); ?>
	<?php Masher::instance('top_head')->add_css('/template/default/css/style.css'); ?>
	<?php echo Masher::instance('top_head')->mark_css(); ?>

	<?php Masher::instance('top_head')->add_js('jquery-2.0.3.min.js'); ?>
	<?php echo Masher::instance('top_head')->mark_js(); ?>

	<?php
	if (isset(View::$_global_data['og:tags']))
	{
		foreach (View::$_global_data['og:tags'] as $tag=>$value)
		{
			if ( ! empty($value))
			{
				?><meta property="<?php echo $tag; ?>" content="<?php echo $value; ?>" /><?php
			}
		}
	}

	?>
</head>

<body>
<?php
echo empty($template['facebook']) ? '{template.facebook}' : View::factory($template['facebook'])->render();
?>

<ol class="breadcrumb">
	<li><a href="/">Home</a></li>
</ol>

<?php
	echo empty($main) ? '{no $main content}' : View::factory($main)->render();
?>

<?php
//echo View::factory('modules/akm2_gravity_points_code_pen')->render();
echo empty($template['header']) ? '{template.header}' : View::factory($template['header'])->render();
echo empty($template['footer']) ? '{template.footer}' : View::factory($template['footer'])->render();
?>

<?php Masher::instance('bottom_body')->add_js('jquery.form.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('3.0.0/bootstrap.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-sanitize-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('underscore-1.5.2.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.cookie.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/jquery.filedrop.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/common.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/app.js'); ?>
<?php echo Masher::instance('bottom_body')->mark_js(); ?>

<script type="text/template" id="feedback">
	<div class="alert">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
		<ul id="errorlist"></ul>
	</div>
</script>

<?php Masher::instance('template_bottom')->add_js('/script/template.js'); ?>
<?php echo Masher::instance('template_bottom')->mark_js(); ?>

</body>
</html>
