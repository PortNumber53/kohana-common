<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 3:22 AM
 *
 */

?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo URL::Site('/', TRUE); ?>"><?php echo Website::get('site_name', '{config::site_name}'); ?></a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<li><a href="#" class="hidden back-link">Back</a></li>
			<!--
			<li class="<?php echo ($current_url==$menu['product_url']) ? 'active' : ''; ?>"><a href="<?php echo $menu['product_url']; ?>">Products</a></li>
			<li class="<?php echo ($current_url==$menu['content_url']) ? 'active' : ''; ?>"><a href="<?php echo $menu['content_url']; ?>">Content</a></li>
			-->
			<li class="<?php echo ($current_url==$menu['gallery_url']) ? 'active' : ''; ?>"><a href="<?php echo $menu['gallery_url']; ?>">Gallery</a></li>
			<li class="<?php echo ($current_url==$menu['privacy_url']) ? 'active' : ''; ?>"><a href="<?php echo $menu['privacy_url']; ?>">Privacy Policy</a></li>
		</ul>
		<form class="navbar-form navbar-left hidden" role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
		<ul class="nav navbar-nav navbar-right" id="user-menu">
			<?php
			if ( ! Account::is_logged_in())
			{
				echo View::factory('modules/unlogged_header')->render();
			}
			else
			{
				echo View::factory('modules/logged_header')->render();
			}
			?>
			<li><a name="top"></a></li>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>
