<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Account
 */
class Model_Account extends Model_Abstract
{
    public static $_table_name = 'account';
    public static $_primary_key = 'accountid';

    public static $_columns = array(
        'accountid' => 0,
        '_id' => '',
        'profile' => '',
        'username' => '',
        'password' => '',
        'display_name' => '',
        'hash' => '',
        'created_at' => '',
        'updated_at' => '',
        'last_login' => '',
        'activation' => '',
        'extra_json' => '',
    );

    public static $_json_columns = array();

    public static function _before_save(&$data = array())
    {
        if (!empty($data['_id'])) {
            $data['_id'] = strtolower($data['_id']);
        } else {
            $data['_id'] = strtolower('/domain/' . $data['username']);
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
        //echo " CACHE: $cache_key<br>";
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where('username', '=', $username);
            $row = $query->execute()->as_array();
            //print_r($row);
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
            } else {
                return false;
            }
        } else {
            $row = json_decode($row, true);
        }
        $row['_id'] = $row['accountid'];
        return $row;
    }

    public static function getAccountByHash($hash)
    {
        $cache_key = '/' . static::$_table_name . ':by_hash:' . $hash;
        //echo " CACHE: $cache_key<br>";
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where('hash', '=', $hash);
            $row = $query->execute()->as_array();
            //print_r($row);
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

                Cache::instance('redis')->set($cache_key, json_encode($row), 360);
            } else {
                return false;
            }
        } else {
            $row = json_decode($row, true);
        }
        $row['_id'] = $row['accountid'];
        return $row;
    }

}
