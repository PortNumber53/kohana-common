<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 3:42 PM
 * Something meaningful about this file
 *
 */

abstract class Model_Abstract extends Model_Core_Abstract
{

	public function get_by_id($_id, &$options=array())
	{
		$cache_key = '/' . $this::$_table_name . ':row:' . json_encode($_id);
		$data = Cache::instance('redis')->get($cache_key);
		if (TRUE || empty($data))
		{
			$query = DB::select()->from($this::$_table_name)->where($this::$_primary_key, '=', $_id);
			$data = $query->execute()->as_array();
			if (count($data) == 1)
			{
				$data = $data[0];
				$json = json_decode($data['extra_json'], TRUE);
				$data = array_merge($data, $json);
				unset($data['extra_json']);
				return $data;
			}
		}
		else
		{
			$data = json_decode($data, TRUE);
		}
		return $data;
	}

	public function get_by_object_id($object_id, &$options=array())
	{
		$cache_key = '/' . $this::$_table_name . ':row:' . json_encode($object_id);
		$data = Cache::instance('redis')->get($cache_key);
		if (TRUE || empty($data))
		{
			$query = DB::select()->from($this::$_table_name)->where('object_id', '=', $object_id);
			$data = $query->execute()->as_array();
			if (count($data) == 1)
			{
				$data = $data[0];
				$json = json_decode($data['extra_json'], TRUE);
				$data = array_merge($data, $json);
				unset($data['extra_json']);
				Cache::instance('redis')->set($cache_key, json_encode($data));
			}
		}
		else
		{
			$data = json_decode($data, TRUE);
		}
		return $data;
	}

	public function get_by_associated_id($associated_id, &$options=array())
	{
		$cache_key = '/' . $this::$_table_name . ':row:' . json_encode($associated_id);
		$data = Cache::instance('redis')->get($cache_key);
		if (TRUE || empty($data))
		{
			$query = DB::select()->from($this::$_table_name)->where('associated_id', '=', $associated_id);
			$data = $query->execute()->as_array();
			if (count($data) >0)
			{
				Cache::instance('redis')->set($cache_key, json_encode($data));
			}
		}
		else
		{
			$data = json_decode($data, TRUE);
		}
		return $data;
	}

	public function save(&$data, &$error, &$options=array())
	{
		$exists = $this->get_by_id($data['_id']);
		if ($exists)
		{
			//$data['password'] = $exists['password'];
		}
		if ( ! empty($data['object_id']))
		{
			$exists2 = $this->get_by_object_id($data['object_id']);
			if ($exists2)
			{
				$exists = $exists2;
			}
		}
		$json_data = array_diff_key($data, $this::$_columns);
		$data = array_intersect_key($data, $this::$_columns);
		$data['extra_json'] = json_encode($json_data);

		ksort($data);
		try
		{
			if (empty($data['object_id']))
			{
				$data['object_id'] = Model_Sequence::nextval();
			}

			if ($exists)
			{
				//Update
				$return = DB::update($this::$_table_name)->set($data)->where('object_id', '=', $data['object_id'])->execute();
			}
			else
			{
				//Insert
				$result = DB::insert($this::$_table_name, array_keys($data))->values($data)->execute();
			}

			//Handle tagging
			if ( ! empty($json_data['tags']))
			{
				$oTagged = new Model_Tagged();
				$oTag = new Model_Tag();
				//Get current tags
				$tag_array = $oTagged->get_by_associated_id($data['object_id']);
				foreach (explode(' ',$json_data['tags']) as $tag)
				{
					$filter = array(
						array('tag', '=', $tag, ),
					);
					$tag_result = $oTag->filter($filter);
					if ( empty($tag_result) )
					{
						//Create tag
						$new_tag_data = array(
							'_id' => '/' . DOMAINNAME . '/' . URL::title($tag),
							'tag' => $tag,
						);
						$result = $oTag->save($new_tag_data, $error);
					}
					else
					{
						$new_tag_data = $tag_result[0];
					}
					//Link object to tag
					$tagged_data = array(
						'_id'           => '/' . $data['object_id'] . '/' . $new_tag_data['object_id'],
						'object_id'     => $new_tag_data['object_id'], //Tag_id
						'associated_id' => $data['object_id'],
					);
					$result_tagged = $oTagged->save($tagged_data, $error);
					foreach ($tag_array as $key=>$value)
					{
						if ($tag_array[$key]['_id'] == $tagged_data['_id'])
						{
							unset($tag_array[$key]);
						}
					}
				}
				foreach ($tag_array as $key=>$value)
				{
					$oTagged->delete_by_id($value['_id']);
				}
			}
		}
		catch (Exception $e)
		{
			$error = array(
				'error' => $e->getCode(),
				'message' => $e->getMessage(),
			);
			return FALSE;
		}
		return TRUE;
	}


	public function filter($filter=array(), $sort=array(), $limit=array())
	{
		$cache_key = '/' . $this::$_table_name . ':filter:' . json_encode($filter) . ':sort:' . json_encode($sort) . ':limit:' . json_encode($limit);
		$data = Cache::instance('redis')->get($cache_key);
		if (TRUE || empty($data))
		{
			$query = DB::select()->from($this::$_table_name);
			if ( ! empty($filter))
			{
				foreach ($filter as $item)
				{
					$query->where($item[0], $item[1], $item[2]);
				}
			}
			$data = $query->execute()->as_array();
			if (count($data) > 0)
			{
				Cache::instance('redis')->set($cache_key, json_encode($data));
			}
		}
		else
		{
			$data = json_decode($data, TRUE);
		}
		return $data;
	}

	public function delete_by_id($_id)
	{
		echo "DELETING $_id\n";
		$query = DB::delete($this::$_table_name)->where($this::$_primary_key, '=', $_id);
		return $query->execute();
	}

	public function delete_by_object_id($object_id, &$error)
	{
		$query = DB::delete($this::$_table_name)->where('object_id', '=', $object_id);
		return $query->execute();
	}
}