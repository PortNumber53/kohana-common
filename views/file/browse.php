<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 5:08 PM
 * Something meaningful about this file
 *
 */

?>

<!-- Indicates a successful or positive action -->
<button type="button" id="btn-new-file" class="btn btn-sm btn-success">+ New File</button>

<table id="tableData" class="table table-striped table-bordered table-hover table-condensed">
	<thead>
		<tr>
			<th width="50">#</th>
			<th>Name</th>
			<th width="200">Action</th>
		</tr>
	</thead>

	<tbody>
	<?php
	foreach ($product_array as $product_data)
	{
	?>
	<tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'file', 'action'=>'edit', 'id'=>$product_data['object_id'], )), TRUE); ?>">
		<td align="right"><?php echo $product_data['object_id']; ?></td>
		<td><?php echo $product_data['name']; ?></td>
		<td><button type="button" class="btn btn-danger btn-xs btn-delete" data-object-id="<?php echo $product_data['object_id']; ?>" data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array('controller'=>'file', 'action'=>'delete', 'id'=>$product_data['object_id'], )), TRUE); ?>">Delete</button></td>
	</tr>
	<?php
	}
	?>
	</tbody>

	<tfoot>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Action</th>
		</tr>
	</tfoot>
</table>


<script>

$(document).ready(function () {
	$("#btn-new-file").on("click", function () {
		document.location = "<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'file', 'action'=>'edit', 'id'=>0)), TRUE); ?>";
	});
	$.removeCookie("back_url");
});
</script>
