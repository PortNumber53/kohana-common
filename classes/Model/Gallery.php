<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Gallery
 */
class Model_Gallery extends Model_Abstract
{
    protected static $_table_name = 'gallery';
    protected static $_primary_key = 'galleryid';

    protected static $_columns = array(
        'galleryid' => 0,
        'categoryid' => 0,
        'status' => '',
        'name' => '',
        'url' => '',
        'description' => '',
        'created_at' => '',
        'updated_at' => '',
        'extra_json' => '{}',
    );

    protected static $_json_columns = array(
        'tags' => '',
    );

    public static function _before_save(&$data = array())
    {
        parent::_before_save($data);
        if (empty($data['object_id'])) {
            $data['object_id'] = Model_Sequence::nextval();
        }
        if (!empty($data['object_id']) && !empty($data['name'])) {
            $data['url'] = '/' . $data['object_id'] . '-' . URL::title($data['name']) . '/';
        }

    }

    public static function _after_save(&$data = array()) {

        //Handle tagging
        if (!empty($data['tags'])) {
            $oTagged = new Model_TagGallery();
            $oTag = new Model_Tag();
            //Get current tags
            //print_r($data);
            //$tag_array = $oTagged->get_by_id($data[static::$_primary_key]);
            $tag_array = $oTagged->get_by_associated_id($data[static::$_primary_key]);
            //print_r($data[static::$_primary_key]); echo  "\n";
            //print_r($tag_array);
            foreach (explode(',', $data['tags']) as $tag) {
                //print_r($tag);echo"\n";
                $filter = array(
                    array('tag', '=', $tag,),
                );
                $tag_result = $oTag->filter($filter);
                //print_r($tag_result);
                if ($tag_result['count'] == 0) {
                    //Create tag
                    $new_tag_data = array(
                        //'_id' => '/' . DOMAINNAME . '/' . URL::title($tag),
                        'galleryid' => $data[static::$_primary_key],
                        'tag' => $tag,
                    );
                    //echo "NEW===\n";
                    $result = $oTag->save($new_tag_data, $error);
                    //print_r($result);
                    //die();
                } else {
                    //echo "ALREADY===\n";
                    $new_tag_data = array_shift($tag_result['rows']);
                    //print_r($new_tag_data);
                }
                //Link object to tag
                $tagged_data = array(
                    //'_id' => '/' . $data[static::$_primary_key] . '/' .
                    //    $new_tag_data['galleryid'],
                    'tagid' => $new_tag_data['tagid'], //Tag_id
                    'galleryid' => $data[static::$_primary_key],
                );
                $result_tagged = $oTagged->save($tagged_data, $error);
                foreach ($tag_array as $key => $value) {
                    if ($tag_array[$key]['tagid'] == $tagged_data['tagid']) {
                        //echo " unsetting:" .$tag_array[$key]['tagid'] . "\n";
                        unset($tag_array[$key]);
                    }
                }
            }
            foreach ($tag_array as $key => $value) {
                $oTagged->deleteById($value['_id']);
            }
            //die($tag);
        }
    }

}
