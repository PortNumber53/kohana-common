<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/28/13
 * Time: 1:17 AM
 * Something meaningful about this file
 *
 */

foreach ($gallery_array as $gallery_data) {
    ?>
    <tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array(
        'controller' => 'file',
        'action' => 'edit',
        'id' => $gallery_data['object_id'],
    )), true); ?>">
        <td align="right"><?php echo $gallery_data['object_id']; ?></td>
        <td><?php echo $gallery_data['name']; ?></td>
        <td>
            <button type="button" class="btn btn-danger btn-xs btn-delete"
                    data-object-id="<?php echo $gallery_data['object_id']; ?>"
                    data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array(
                        'controller' => 'file',
                        'action' => 'delete',
                        'id' => $gallery_data['object_id'],
                    )), true); ?>">Delete
            </button>
        </td>
    </tr>
    <?php
}
