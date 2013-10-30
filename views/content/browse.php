<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/30/13
 * Time: 12:52 AM
 * Something meaningful about this file
 *
 */

?>

<!-- Indicates a successful or positive action -->
<button type="button" id="btn-new-content" class="btn btn-sm btn-success">+ New Content</button>

<table id="tableData" class="table table-striped table-bordered table-hover table-condensed">
	<thead>
	<tr>
		<th width="50">#</th>
		<th>Title</th>
		<th width="200">Action</th>
	</tr>
	</thead>

	<tbody>
	<?php
	foreach ($content_array as $content_data)
	{
		?>
		<tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'content', 'action'=>'edit', 'id'=>$content_data['object_id'], )), TRUE); ?>">
			<td align="right"><?php echo $content_data['object_id']; ?></td>
			<td><?php echo $content_data['name']; ?></td>
			<td><button type="button" class="btn btn-danger btn-xs btn-delete" data-object-id="<?php echo $content_data['object_id']; ?>" data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array('controller'=>'content', 'action'=>'delete', 'id'=>$content_data['object_id'], )), TRUE); ?>">Delete</button></td>
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
		$("#btn-new-content").on("click", function () {
			document.location = "<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'content', 'action'=>'edit', 'id'=>0)), TRUE); ?>";
		});
		$.removeCookie("back_url");
	});
</script>
