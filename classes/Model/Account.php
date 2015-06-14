<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Date: 5/15/13
 * Time: 11:16 PM
 *
 */
class Model_Account extends Model_Abstract
{
    public static $_table_name = 'account';
    public static $_primary_key = '_id';

    public static $_columns = array(
        '_id' => '',
        'object_id' => '',
        'profile' => '',
        'username' => '',
        'password' => '',
        'name' => '',
        'avatar' => 'picture',
        'email' => '',
        'hash' => '',
        'created_at' => '',
        'modified_at' => '',
        'extra_json' => '',
    );

    public static $_json_columns = array();

    public function _before_save(&$data = array())
    {
        if (!empty($data['_id'])) {
            $data['_id'] = strtolower($data['_id']);
        }
        if (!empty($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }
        if (!empty($data['username'])) {
            $data['username'] = strtolower($data['username']);
        }
        unset($data['password1'], $data['password2'], $data['remember_me']);
        parent::_before_save($data);
    }

    public static function getAccountByUsername($username)
    {
        $cache_key = '/' . static::$_table_name . ':row:' . $username;
        $row = Cache::instance('redis')->get($cache_key);
        if (empty($row))
        {
            $query = DB::select()->from(static::$_table_name)->where('username', '=', $username);
            $row = $query->execute()->as_array();
            if (count($row) == 1)
            {
                $row = array_shift($row);
                $data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), TRUE);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row, 'extra_json', '{}'), TRUE);
                unset($extra_json['_id']);
                $row = array_merge($row, $extra_json);
                unset($row['extra_json']);

                Cache::instance('redis')->set($cache_key, json_encode($row));
                return $row;
            }
        }
        return json_decode($row, true);
    }

}
