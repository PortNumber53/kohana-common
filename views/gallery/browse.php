<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/27/13
 * Time: 5:08 PM
 * Something meaningful about this gallery
 *
 */

?>

<!-- Indicates a successful or positive action -->
<button type="button" id="btn-new-gallery" class="btn btn-sm btn-success">+ New Gallery</button>

<table id="tableData" class="table table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="50">#</th>
        <th>Name</th>
        <th width="125">Action</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($gallery_array as $gallery_data) {
        ?>
        <tr class="clickable" data-edit-link="<?php echo URL::Site(Route::get('default')->uri(array(
            'controller' => 'gallery',
            'action' => 'edit',
            'id' => $gallery_data['galleryid'],
        )), true); ?>">
            <td align="right"><?php echo $gallery_data['galleryid']; ?></td>
            <td><?php echo $gallery_data['name']; ?></td>
            <td>
                <button type="button" class="btn btn-danger btn-xs btn-delete"
                        data-object-id="<?php echo $gallery_data['galleryid']; ?>"
                        data-delete-link="<?php echo URL::Site(Route::get('service-actions')->uri(array(
                                'controller' => 'gallery',
                                'action' => 'delete',
                                'id' => $gallery_data['galleryid'],
                            )), true) . '/'; ?>">Delete
                </button>
                <button type="button" class="btn btn-primary btn-xs btn-manage"
                        data-object-id="<?php echo $gallery_data['galleryid']; ?>"
                        data-action-link="<?php echo URL::Site(Route::get('default')->uri(array(
                                'controller' => 'gallery',
                                'action' => 'manage',
                                'id' => $gallery_data['galleryid'],
                            )), true) . '/'; ?>">Manage
                </button>
            </td>
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
<?php echo $page_links; ?>


<script>

    $(document).ready(function () {
        $("#btn-new-gallery").on("click", function () {
            document.location = "<?php echo URL::Site(Route::get('default')->uri(array('controller'=>'gallery', 'action'=>'edit', 'id'=>0)), true); ?>";
        });
        $.removeCookie("back_url");
    });
</script>
