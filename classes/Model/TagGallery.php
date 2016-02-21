<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Tag_Gallery
 */
class Model_TagGallery extends Model_Abstract
{
    protected static $_table_name = 'tag_gallery';
    protected static $_primary_key = array('tagid', 'galleryid');

    protected static $_columns = array(
        'tagid' => '',
        'galleryid' => 0,
        'created_at' => '',
        'modified_at' => '',
        'extra_json' => '{}',
    );

}
