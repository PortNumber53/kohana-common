<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/22/13
 * Time: 11:48 PM
 * Something meaningful about this file
 *
 */

$class_image = '';
if ($filtered_gallery['rows'] > 1) {
    $class_image = 'multiple';
}
$class = "$class_image";

?>
    <ul class="blog-list<?php echo " $class"; ?>">
        <?php
        foreach ($filtered_gallery['rows'] as $gallery_data) {
            $gallery_data['canonical_url'] = URL::Site(Route::get('blog-actions')->uri(array(
                'id' => $gallery_data['galleryid'],
                'slug' => URLify::filter($gallery_data['name']),
            )), true);
            if (substr($gallery_data['canonical_url'], -1) != '/') {
                $gallery_data['canonical_url'] = $gallery_data['canonical_url'] . '/';
            }
            ?>
            <li>
                <?php
                echo View::factory('gallery/blog/post', array(
                    'gallery_data' => $gallery_data,
                    'picture_data' => array(
                        'rows' => array(
                            array(
                                'image_url' => $gallery_data['image_url'],
                            )
                        ),
                    ),
                ))->render();
                ?>
            </li>
            <li>
                <hr/>
            </li><?php
        }
        ?>
    </ul>
<?php
echo $page_links;
