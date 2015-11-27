<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 10/26/13
 * Time: 3:42 PM
 * Something meaningful about this file
 *
 */
abstract class Model_Abstract extends Model_Core_Abstract
{
    static protected function _add_domain(&$_id)
    {
        if (!empty($_id) && substr($_id, -1) !== '/') {
            $temp_id = parse_url($_id, PHP_URL_PATH);
            $path_parts = pathinfo($temp_id);
            if (empty($path_parts['extension'])) {
                $_id = $_id . '/';
            }
        }
        if (substr($_id, 0, 1) != '/') {
            $_id = "/$_id";
        }
        if (strpos($_id, DOMAINNAME) === false) {
            $_id = '/' . DOMAINNAME . $_id;
        }
    }

    public function get_by_id($_id, &$options = array())
    {
        try {
            //self::_add_domain($_id);
            $cache_key = '/' . $this::$_table_name . ':row:' . $_id;
            $row = Cache::instance('redis')->get($cache_key);
            if (true || empty($row)) {
                $query = DB::select()->from(static::$_table_name)->where(static::$_primary_key, '=', $_id);
                $row = $query->execute()->as_array();
                if (count($row) === 1) {
                    $row = array_shift($row);
                    $data = Arr::path($row, 'data');
                    $data = json_decode(empty($data) ? '{}' : Arr::path($row, 'data', '{}'), true);
                    unset($data['_id']);
                    $row = array_merge($row, $data);
                    unset($row['data']);
                    $extra_json = Arr::path($row, 'extra_json');
                    $extra_json = json_decode(empty($extra_json) ? '{}' : Arr::path($row, 'extra_json', '{}'), true);
                    unset($extra_json['_id']);
                    $row = array_merge($row, $extra_json);
                    unset($row['extra_json']);

                    Cache::instance('redis')->set($cache_key, json_encode($row));
                    return $row;
                } else {
                    return false;
                }
            } else {
                $row = json_decode($row, true);
            }
            return $row;
        } catch (Exception $e) {
            $error = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            );
            return false;
        } finally {
            return $row;
        }
    }

    public static function _getDataById($id)
    {
        $cache_key = '/' . static::$_table_name . ':row:' . $id;
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where(static::$_primary_key, '=', $id);
            $row = $query->execute()->as_array();
            if (count($row) == 1) {
                $row = array_shift($row);
                $data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), true);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row, 'extra_json',
                    '{}'), true);
                unset($extra_json['_id']);
                $row = array_merge($row, $extra_json);
                unset($row['extra_json']);

                Cache::instance('redis')->set($cache_key, json_encode($row));
            }
        } else {
            $row = json_decode($row, true);
        }

        // Data check stuff?
        return $row;
    }

    public static function getDataBySeo($seoName)
    {
        $cache_key = '/' . static::$_table_name . ':row:' . $seoName;
        $data = Cache::instance('redis')->get($cache_key);
        if (empty($data)) {
            $query = DB::select()->from(static::$_table_name)->where('name_seo', '=', $seoName);
            $data = $query->execute()->as_array();
            if (count($data) == 1) {
                $data = $data[0];
                if (isset($data['extra_json'])) {
                    $json = json_decode($data['extra_json'], true);
                    $data = array_merge($data, $json);
                    unset($data['extra_json']);
                }
                Cache::instance('redis')->set($cache_key, json_encode($data));
            }
        } else {
            $data = json_decode($data, true);
        }
        return $data;
    }

    public function get_by_object_id($object_id, &$options = array())
    {
        $cache_key = '/' . $this::$_table_name . ':row:' . $object_id;
        $data = Cache::instance('redis')->get($cache_key);
        if (empty($data)) {
            $query = DB::select()->from($this::$_table_name)->where('object_id', '=', $object_id);
            $data = $query->execute()->as_array();
            if (count($data) == 1) {
                $data = $data[0];
                $json = json_decode($data['extra_json'], true);
                $data = array_merge($data, $json);
                unset($data['extra_json']);
                Cache::instance('redis')->set($cache_key, json_encode($data));
            }
        } else {
            $data = json_decode($data, true);
        }
        return $data;
    }

    public static function getDataByParentId($parentId, $filters = array(), $limit = 0, $offset = 0)
    {
        return static::_getDataByParentId($parentId, $filters, $limit, $offset);
    }

    public static function _getDataByParentId($parentId, $filters, $limit, $offset)
    {
        // TODO: Implement _getDataByParentId() method._saveRow
    }

    public function get_by_associated_id($associated_id, &$options = array())
    {
        $cache_key = '/' . $this::$_table_name . ':row:' . $associated_id;
        $data = Cache::instance('redis')->get($cache_key);
        if (empty($data)) {
            $query = DB::select()->from($this::$_table_name)->where('associated_id', '=', $associated_id);
            $data = $query->execute()->as_array();
            if (count($data) > 0) {
                Cache::instance('redis')->set($cache_key, json_encode($data));
            }
        } else {
            $data = json_decode($data, true);
        }
        return $data;
    }

    public static function _before_save(&$data = array())
    {
        // TODO: Implement _before_save() method.
        if (!empty($data['_id']) && substr($data['_id'], -1) !== '/') {
            $_id = parse_url($data['_id'], PHP_URL_PATH);
            $path_parts = pathinfo($_id);
            if (empty($path_parts['extension'])) {
                $data['_id'] = $data['_id'] . '/';
            }
        }
        self::_add_domain($data['_id']);
    }

    public function save(&$data, &$error, &$options = array())
    {
        $this->_before_save($data);
        $exists = false;

        if (is_array(static::$_primary_key)) {
            foreach (static::$_primary_key as $key) {
                if (!isset($data[$key])) {
                    $data[$key] = 0;
                }
            }
        } else {
            if (!isset($data[static::$_primary_key])) {
                $data[static::$_primary_key] = 0;
            }
        }

        $update_filter = 'object_id';
        if (!empty($data['object_id'])) {
            $exists = $this->get_by_object_id($data['object_id']);
        }
        if (!$exists) {
            if (is_array(static::$_primary_key)) {
                $filter = array();
                foreach (static::$_primary_key as $key) {
                    $filter[] = array($key, '=', $data[$key]);
                }
                $tmp = $this->filter($filter);
                $exists = ($tmp['count'] > 0) ? array_shift($tmp['rows']) : array();
            } else {
                if ($data[static::$_primary_key] !== 0) {
                    $exists = $this->get_by_id($data[static::$_primary_key]);
                    $update_filter = '_id';
                }
            }
        }
        if ($exists) {
            $data = array_merge($exists, $data);
        }

        $json_data = array_diff_key($data, $this::$_columns);
        $data = array_intersect_key($data, $this::$_columns);
        if (empty($options['no_extra_json'])) {
            $data['extra_json'] = json_encode($json_data);
        }

        ksort($data);
        try {
            if ($exists) {
                //Update
                $data['updated_at'] = date('Y-m-d H:i:s');
                if (is_array(static::$_primary_key)) {
                    $query = DB::update($this::$_table_name)->set($data);
                    foreach (static::$_primary_key as $loop_key) {
                        $query->where($loop_key, '=', $data[$loop_key]);
                    }
                } else {
                    $query = DB::update($this::$_table_name)->set($data)->where(static::$_primary_key, '=',
                        $data[static::$_primary_key]);
                }
                $result = $query->execute();
            } else {
                //Insert
                $result = DB::insert($this::$_table_name, array_keys($data))->values($data)->execute();
                if (!is_array(static::$_primary_key)) {
                    $data[static::$_primary_key] = $result[0];
                }
            }
            if (!empty($data['object_id'])) {
                $cache_key = '/' . $this::$_table_name . ':row:' . $data['_id'];
                Cache::instance('redis')->delete($cache_key);
            }

            //Handle tagging
            if (!empty($json_data['tags'])) {
                $oTagged = new Model_Tagged();
                $oTag = new Model_Tag();
                //Get current tags
                $tag_array = $oTagged->get_by_associated_id($data['object_id']);
                foreach (explode(',', $json_data['tags']) as $tag) {
                    $filter = array(
                        array('tag', '=', $tag,),
                    );
                    $tag_result = $oTag->filter($filter);
                    if ($tag_result['count'] == 0) {
                        //Create tag
                        $new_tag_data = array(
                            '_id' => '/' . DOMAINNAME . '/' . URL::title($tag),
                            'tag' => $tag,
                        );
                        $result = $oTag->save($new_tag_data, $error);
                    } else {
                        $new_tag_data = $tag_result['rows'][0];
                    }
                    //Link object to tag
                    $tagged_data = array(
                        '_id' => '/' . $data['object_id'] . '/' . $new_tag_data['object_id'],
                        'object_id' => $new_tag_data['object_id'], //Tag_id
                        'associated_id' => $data['object_id'],
                    );
                    $result_tagged = $oTagged->save($tagged_data, $error);
                    foreach ($tag_array as $key => $value) {
                        if ($tag_array[$key]['_id'] == $tagged_data['_id']) {
                            unset($tag_array[$key]);
                        }
                    }
                }
                foreach ($tag_array as $key => $value) {
                    $oTagged->delete_by_id($value['_id']);
                }
            }
        } catch (Exception $e) {
            $error = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => __FILE__,
            );
            print_r($error);
            return false;
        }
        return $result;
    }


    public static function _saveRow($data, &$error = array())
    {
        //static::_before_save($data);
        if (empty($data[static::$_primary_key])) {
            $exists = false;
        } else {
            $cache_key = '/' . static::$_table_name . ':row:' . $data[static::$_primary_key];
            $exists = DB::select()->from(static::$_table_name)->where(static::$_primary_key, '=',
                $data[static::$_primary_key])->execute()->count();
        }
        if ($exists) {
            //Update
            //echo static::$_table_name.' update';
            $query = DB::update(static::$_table_name)->set($data)->where(static::$_primary_key, '=',
                $data[static::$_primary_key]);
            $affected_rows = $query->execute();
        } else {
            //Insert
            //echo static::$_table_name.' insert';
            list($insert_id, $affected_rows) = DB::insert(static::$_table_name,
                array_keys($data))->values($data)->execute();
            $data[static::$_primary_key] = $insert_id;
        }
        $cache_key = '/' . static::$_table_name . ':row:' . $data[static::$_primary_key];
        // $cache_key;

        Cache::instance('redis')->delete($cache_key);
        return $data;
    }

    public function filter($filter = array(), $sort = array(), $limit = array(), $offset = array())
    {
        $cache_key = '/' . $this::$_table_name . ':filter:' . json_encode($filter) . ':sort:' . json_encode($sort) . ':limit:' . json_encode($limit) . ':offset:' . json_encode($offset);
        $filter_data = Cache::instance('redis')->get($cache_key);
        if (true || empty($data)) {
            $query = DB::select()->from($this::$_table_name);
            if (!empty($filter)) {
                foreach ($filter as $item) {
                    $query->and_where($item[0], $item[1], $item[2]);
                }
            }
            $pagination_query = clone $query;
            $count = $pagination_query->select(DB::expr('COUNT(*) AS mycount'))->execute()->get('mycount');

            if (!empty($limit)) {
                $query->limit($limit);
            }
            if (!empty($offset)) {
                $query->offset($offset);
            }
            if (!empty($sort)) {
                foreach ($sort as $column => $order) {
                    $query->order_by($column, $order);
                }
            }

            $return_data = array();
            $result = $query->execute()->as_array();
            foreach ($result as $row) {
                if (is_array(static::$_primary_key)) {
                    $key = '';
                    foreach (static::$_primary_key as $loop_key) {
                        $key .= $row[$loop_key] . ',';
                    }
                    $key = substr($key, 0, -1);
                    $return_data[$key] = $row;
                } else {
                    $return_data[$row[static::$_primary_key]] = $row;
                }
            }

            $filter_data = array(
                'rows' => $return_data,
                'count' => (int)$count,
                'limit' => $limit,
                'offset' => $offset,
                'pages' => empty($limit) ? 1 : (ceil($count / (($limit === 0) ? 1 : $limit))),
            );

            if (count($filter_data['rows']) > 0) {
                foreach ($filter_data['rows'] as $key => &$row) {
                    $data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), true);
                    $row = array_merge($row, $data);
                    unset($row['data']);
                    $extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row,
                        'extra_json', '{}'), true);
                    $row = array_merge($row, $extra_json);
                    unset($row['extra_json']);
                }
                Cache::instance('redis')->set($cache_key, $filter_data);
            }
        } else {
            $data = json_decode($filter_data, true);
        }
        return $filter_data;
    }

    public function delete_by_id($_id)
    {
        $query = DB::delete($this::$_table_name)->where($this::$_primary_key, '=', $_id);
        return $query->execute();
    }

    public function delete_by_object_id($object_id, &$error)
    {
        $query = DB::delete($this::$_table_name)->where('object_id', '=', $object_id);
        return $query->execute();
    }
}
