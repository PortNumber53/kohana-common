<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/28/13
 * Time: 1:17 AM
 * Something meaningful about this file
 *
 */

foreach ($product_array as $product_data)
{
	?>
	<tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'product', 'action'=>'edit', 'id'=>$product_data['object_id'], )), TRUE); ?>">
		<td align="right"><?php echo $product_data['object_id']; ?></td>
		<td><?php echo $product_data['name']; ?></td>
		<td><button type="button" class="btn btn-danger btn-xs btn-delete" data-object-id="<?php echo $product_data['object_id']; ?>" data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array('controller'=>'product', 'action'=>'delete', 'id'=>$product_data['object_id'], )), TRUE); ?>">Delete</button></td>
	</tr>
<?php
}
?>
