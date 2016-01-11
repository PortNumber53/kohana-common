<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 6:54 PM
 * Something meaningful about this file
 *
 */

?>
<form class="form-horizontal json-form" role="form" method="post"
      action="<?php echo URL::Site(Route::get('default')->uri(array(
              'controller' => 'gallery',
              'action' => 'edit',
              'id' => $gallery_data['galleryid'],
          )), true) . '/'; ?>">
    <div class="form-group">
        <label for="inputName" class="col-lg-2 control-label">Name</label>

        <div class="col-lg-9">
            <input type="text" class="form-control" id="inputName" placeholder="Gallery name" name="name"
                   value="<?php echo Arr::path($gallery_data, 'name', ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputStatus" class="col-lg-2 control-label">Status</label>

        <div class="col-lg-9">
            <input type="text" class="form-control" id="inputStatus" placeholder="Gallery Status" name="status"
                   value="<?php echo Arr::path($gallery_data, 'status', ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputDescription" class="col-lg-2 control-label">Description</label>

        <div class="col-lg-9">
            <textarea class="form-control" id="inputDescription" placeholder="Description" name="description"
                      rows="10"><?php echo Arr::path($gallery_data, 'description', ''); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="inputTags" class="col-lg-2 control-label">Tags</label>

        <div class="col-lg-9">
            <input type="text" class="form-control" id="inputTags" placeholder="Gallery Tags" name="tags"
                   value="<?php echo Arr::path($gallery_data, 'tags', ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-9">
            <button type="submit" class="btn btn-default">Update Gallery Information</button>
        </div>
    </div>
</form>
