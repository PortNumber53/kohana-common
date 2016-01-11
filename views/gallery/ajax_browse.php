<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/28/13
 * Time: 1:17 AM
 * Something meaningful about this file
 *
 */

foreach ($gallery_array['rows'] as $gallery_data) {
    ?>
    <tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array(
        'controller' => 'file',
        'action' => 'edit',
        'id' => $gallery_data['galleryid'],
    )), true); ?>">
        <td align="right"><?php echo $gallery_data['galleryid']; ?></td>
        <td><?php echo $gallery_data['name']; ?></td>
        <td>
            <button type="button" class="btn btn-danger btn-xs btn-delete"
                    data-object-id="<?php echo $gallery_data['galleryid']; ?>"
                    data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array(
                        'controller' => 'file',
                        'action' => 'delete',
                        'id' => $gallery_data['galleryid'],
                    )), true); ?>">Delete
            </button>
        </td>
    </tr>
    <?php
}
