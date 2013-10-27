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
?>EDIT!


<form name="frm_edit" method="post" action="<?php echo URL::site(Request::detect_uri(), TRUE); ?>">

	ID: <input type="text" name="_id" id="_id" size="200" value="<?php echo empty($data['_id']) ? '' : $data['_id']; ?>" />
	<br />
	URL: <input type="text" name="url" id="url" size="40" value="<?php echo empty($data['url']) ? '' : $data['url']; ?>" />
	<br />
	<select id="mimetype" name="mimetype">
		<option value="">Force a MimeType</option>
		<option value="text/plain"<?php if ($data['mimetype'] == 'text/plain') { echo " SELECTED";} ?>>Text/Plain</option>
		<option value="text/html"<?php if ($data['mimetype'] == 'text/html') { echo " SELECTED";} ?>>Text/HTML</option>
	</select>
	<br />
	Title: <input type="text" name="title" id="title" size="40" value="<?php echo empty($data['title']) ? '' : $data['title']; ?>" />
	<br />
	Body: <textarea name="body" id="body" cols="200" rows="15"><?php echo empty($data['body']) ? '' : htmlentities($data['body']); ?></textarea>
	<br />
	<br />
	<button name="btnaction" id="btnaction_update" class="form_submit button blue flat"> Update </button>


</form>

