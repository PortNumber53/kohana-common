<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/22/13
 * Time: 11:48 PM
 * Something meaningful about this file
 *
 */

?>
<ul class="blog-list">
	<?php
	foreach ($filtered_gallery['rows'] as $gallery_data)
	{
		$gallery_data['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array('id'=>$gallery_data['object_id'], 'slug'=>URLify::filter($gallery_data['name']), )), TRUE);
		if (substr($gallery_data['canonical_url'], -1) != '/')
		{
			$gallery_data['canonical_url'] = $gallery_data['canonical_url'] . '/';
		}
		?>
		<li>
			<?php
			echo View::factory('gallery/blog/post', array(
				'gallery_data' => $gallery_data,
			))->render();
			?>
		</li>
		<li>
		<hr />
		</li><?php
	}
	?>
</ul>
<?php
echo $page_links;
