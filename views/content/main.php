<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:21 AM
 *
 */

?>
<article>
<a href="<?php echo $data['url']; ?>"><h1><?php echo $data['title']; ?></h1></a>

<section>
	<?php
	switch (empty($data['mimetype']) ? '' : $data['mimetype'])
	{
		case "text/plain":
		default:
			if (empty($data['body']))
			{
				foreach ($data['sections'] as $section_data)
				{
					?><header><h1><?php echo $section_data['title']; ?></h1></header><?php
					?><div><?php echo $section_data['content']; ?></div><?php
					if (! empty($section_data['picture']))
					{
						?><img class="lazy" data-src="<?php echo $section_data['picture']; ?>" src="<?php echo $section_data['picture']; ?>" /><?php
					}
				}
			}
			else
			{
				echo nl2br(html_entity_decode($data['body']));
			}
			break;
	}
	?>
</section>

</article>
