<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/22/13
 * Time: 11:48 PM
 * Something meaningful about this file
 *
 */

?>
<h1>Infinite scrolling, here it goes!</h1>
<ul class="blog-list">
	<?php
	foreach ($gallery_data as $item)
	{
		?><li>
			<article>
				<header>
					<h2><?php echo $item['name']; ?></h2>
				</header>
				<section>
					<?php echo $item['description']; ?>
					<ul>
					<?php foreach ($item['file_list'] as $file)
					{
						?><li><?php echo HTML::image($file['image']['url']); ?></li><?php
					}
					?>
					</ul>
				</section>

				<footer>
					source: ? / uploaded: <?php echo date("M, d Y @ H:i:s", strtotime(Arr::path($item, 'created_at'))); ?>
				</footer>
			</article>
		</li><?php
	}
	?>
</ul>


<?php
echo '<pre>';print_r($gallery_data);echo'</pre>';