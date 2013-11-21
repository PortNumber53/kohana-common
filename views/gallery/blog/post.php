<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/19/13
 * Time: 12:41 AM
 * Something meaningful about this file
 *
 */
$file_list = Arr::path($gallery_data, 'file_list', array());

if ( !  empty($file_list))
{
	$image_route = URL::Site('/' . $file_list[0]['image']['url'], TRUE);
}

//echo '<pre>';
//var_dump($gallery_data);
//echo '</pre>';
?>






<article>

	<header>
		<h1><?php echo $gallery_data['name']; ?></h1>
	</header>

	<ul class="social-links clearfix">
		<li>
			<div class="fb-like" data-href="<?php echo $canonical_url; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
		</li>
		<li>
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($canonical_url); ?>&media=<?php echo urlencode($image_route); ?>&description=<?php echo urlencode($gallery_data['name']); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
		</li>
	</ul>

	<br />

	<section class="clearfix">
		<?php echo $gallery_data['description']; ?>
	</section>

	<?php
	if ( ! empty($file_list))
	{
	?>
		<ul class="blog-list clearfix">
			<?php
			foreach ($file_list as $item)
			{
				?><li><img src="<?php echo URL::Site($item['image']['url'], TRUE); ?>" /></li><?php
			}
			?>
		</ul>
	<?php
	}
	?>


</article>