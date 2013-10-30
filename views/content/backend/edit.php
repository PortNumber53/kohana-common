<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/17/13
 * Time: 1:37 AM
 *
 */
if (empty($data['mimetype']))
{
	$data['mimetype'] = 'text/plain';
}
?>


<form class="form-horizontal json-form" role="form" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">
	<div class="form-group">
		<label for="inputID" class="col-lg-2 control-label">_ID</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputID" placeholder="_id" name="id" value="<?php echo Arr::path($content_data, '_id', ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputUrl" class="col-lg-2 control-label">URL</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputUrl" placeholder="Content URL" name="url" value="<?php echo Arr::path($content_data, 'url', ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputMimeType" class="col-lg-2 control-label">MimeType</label>
		<div class="col-lg-9">
			<select id="inputMimeType" name="mimetype" class="form-control">
				<option value="">Force a MimeType</option>
				<option value="text/plain"<?php if (Arr::path($content_data, 'mimetype', '') == 'text/plain') { echo " SELECTED";} ?>>Text/Plain</option>
				<option value="text/html"<?php if (Arr::path($content_data, 'mimetype', '') == 'text/html') { echo " SELECTED";} ?>>Text/HTML</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="inputTitle" class="col-lg-2 control-label">Title</label>
		<div class="col-lg-9">
			<input type="text" class="form-control" id="inputTitle" placeholder="Title" name="title" value="<?php echo Arr::path($content_data, 'title', ''); ?>">
		</div>
	</div>


	<div class="form-group">
		<label for="inputBody" class="col-lg-2 control-label">Body</label>
		<div class="col-lg-9">
			<textarea class="form-control" id="inputBody" placeholder="Content Body" name="body" rows="10"><?php echo Arr::path($content_data, 'body', ''); ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-9">
			<button type="submit" class="btn btn-default">Update Content</button>
		</div>
	</div>
</form>

