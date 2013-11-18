<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/17/13
 * Time: 12:31 PM
 * Something meaningful about this file
 *
 */

?>
<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'gallery', 'action'=>'edit', 'id'=>$gallery_data['object_id'], )), TRUE); ?>">
	<div class="form-group">
		<label for="inputName" class="col-lg-2 control-label">Name</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputName" placeholder="Gallery name" name="name" value="<?php echo Arr::path($gallery_data, 'name', ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputStatus" class="col-lg-2 control-label">Status</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputStatus" placeholder="Gallery Status" name="status" value="<?php echo Arr::path($gallery_data, 'status', ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputDescription" class="col-lg-2 control-label">Images</label>
		<div class="col-lg-9">
			<ul class="thumbnail-list dropbox-container">
				<li>
					<div id="dropbox" data-dropbox-behavior="gallery" class="dropbox-gallery text-center center-block" data-field="profile_avatar">
						Drop images here to upload more
					</div>
				</li>
				<li>Images here</li>
			</ul>
		</div>
	</div>
	<div class="form-group">
		<label for="inputTags" class="col-lg-2 control-label">Tags</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputTags" placeholder="Gallery Tags" name="tags" value="<?php echo Arr::path($gallery_data, 'tags', ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-9">
			<button type="button" class="btn btn-primary btn-xs btn-form-action" data-object-id="<?php echo $gallery_data['object_id']; ?>" data-action-link="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'gallery', 'action'=>'edit', 'id'=>$gallery_data['object_id'], )), TRUE); ?>">Back to Edit Gallery</button>
			<button type="button" class="btn btn-primary btn-xs btn-form-action" data-object-id="<?php echo $gallery_data['object_id']; ?>" data-action-link="<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'gallery', 'action'=>'update_list', 'id'=>$gallery_data['object_id'], )), TRUE); ?>">Update Image List</button>
			<button type="submit" class="btn btn-default">Update Gallery Information</button>
		</div>
	</div>
</form>