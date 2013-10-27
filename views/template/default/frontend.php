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

	<?php Masher::instance('top_head')->add_css('bootstrap.min.css'); ?>
	<?php Masher::instance('top_head')->add_css('/template/default/css/style.css'); ?>
	<?php echo Masher::instance('top_head')->mark_css(); ?>



</head>

<body>
<?php
echo empty($template['header']) ? '{template.header}' : View::factory($template['header'])->render();
?>


<?php
	echo empty($main) ? '{no $main content}' : $main;
?>



<?php
//echo View::factory('modules/akm2_gravity_points_code_pen')->render();
?>

<?php
echo empty($template['footer']) ? '{template.footer}' : View::factory($template['footer'])->render();
?>
</body>
</html>