<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:21 AM
 *
 */

?>
<section>
<a href="<?php echo $data['url']; ?>"><h1><?php echo $data['title']; ?></h1></a>

<div>
	<?php
	switch (empty($data['mimetype']) ? '' : $data['mimetype'])
	{
		case "text/plain":
		default:
			echo html_entity_decode($data['body']);
			break;
	}
	?>
</div>

</section>