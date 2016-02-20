<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/19/13
 * Time: 12:41 AM
 * Something meaningful about this file
 *
 */
$file_list = Arr::path($gallery_data, 'file_list', array());

$image_route = '';
if (!empty($file_list)) {
    $image_route = URL::Site('/' . $file_list[0]['image']['url'], true);
}
?>

<article>

    <header>
        <a href="<?php echo $gallery_data['canonical_url']; ?>"><h1><?php echo $gallery_data['name']; ?></h1></a>
    </header>

    <ul class="social-links clearfix">
        <li>
            <div class="fb-share-button" data-href="<?php echo $gallery_data['canonical_url']; ?>"
                 data-type="button_count"></div>
        </li>
        <li>
            <div class="g-plusone" data-href="<?php echo $gallery_data['canonical_url']; ?>" data-size="medium"
                 data-annotation="bubble"></div>
        </li>
        <li>
            <su:badge layout="2" location="<?php echo $gallery_data['canonical_url']; ?>"></su:badge>
        </li>
        <li>
            <a href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode($gallery_data['canonical_url']); ?>&media=<?php echo urlencode($image_route); ?>&description=<?php echo urlencode($gallery_data['name']); ?>"
               data-pin-do="buttonPin" data-pin-config="beside"><img
                    src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png"/></a>
        </li>
    </ul>

    <br/>

    <section class="clearfix">
        <?php echo $gallery_data['description']; ?>
    </section>

    <?php
    if (!empty($picture_data)) {
        ?>
        <ul class="blog-list clearfix">
            <?php
            foreach ($picture_data['rows'] as $row) {
                ?>
                <li>
                    <img src="<?php echo URL::Site($row['image_url'], true); ?>"/>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }
    ?>
    <footer>
        source: ? / uploaded: <?php echo date("M, d Y @ H:i:s", strtotime(Arr::path($gallery_data, 'created_at'))); ?>
    </footer>

    <?php
    if (isset($single_post) && $single_post) {
        ?>
        <div class="fb-comments" data-href="<?php echo $gallery_data['canonical_url']; ?>" data-numposts="5"
             data-colorscheme="light"></div>
        <?php
    }
    ?>

</article>
