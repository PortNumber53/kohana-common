<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 3:44 PM
 * Something meaningful about this file
 *
 */

class Cache_Redis extends Cache implements Cache_Arithmetic {

	// Redis has a maximum cache lifetime of 30 days
	const CACHE_CEILING = 2592000;

	/**
	 * Redis resource
	 *
	 * @var Redis
	 */
	protected $_Redis;

	/**
	 * Flags to use when storing values
	 *
	 * @var string
	 */
	protected $_flags;

	/**
	 * The default configuration for the Redisd server
	 *
	 * @var array
	 */
	protected $_default_config = array();

	/**
	 * Constructs the Redis Kohana_Cache object
	 *
	 * @param   array  $config  configuration
	 * @throws  Cache_Exception
	 */
	protected function __construct(array $config)
	{
		// Check for the Redis extention
		if ( ! extension_loaded('Redis'))
		{
			throw new Cache_Exception('Redis PHP extention not loaded');
		}

		parent::__construct($config);

		// Setup Redis
		$this->_Redis = new Redis();

		// Load servers from configuration
		$servers = Arr::get($this->_config, 'servers', NULL);

		if ( ! $servers)
		{
			// Throw an exception if no server found
			throw new Cache_Exception('No Redis servers defined in configuration');
		}

		// Setup default server configuration
		$this->_default_config = array(
			'host'             => 'localhost',
			'port'             => 6379,
			'persistent'       => FALSE,
			'timeout'          => 10,
			'pconnect'         => TRUE,
		);

		// Add the Redis servers to the pool
		foreach ($servers as $server)
		{
			// Merge the defined config with defaults
			$host = isset($config['host']) && ($config['host']) ? $config['host'] : $this->_default_config['host'];
			$port = isset($config['port']) && ($config['port']) ? $config['port'] : 6379;
			$timeout = isset($config['timeout']) && ($config['timeout']) ? $config['timeout'] : 1;
			$pconnect = isset($config['pconnect']) && ($config['pconnect']) ? $config['pconnect'] : TRUE;

			// Persistent connection
			if ($pconnect === TRUE)
			{
				$this->_Redis->pconnect($host, $port, $timeout);
			}
			// Non persistent connection
			else
			{
				$this->_Redis->connect($host, $port, $timeout);
			}
			//if ( ! $this->_Redis->addServer($server['host'], $server['port'], $server['persistent'], $server['weight'], $server['timeout'], //$server['retry_interval'], $server['status'], $server['failure_callback']))
			//{
			//	throw new Cache_Exception('Redis could not connect to host \':host\' using port \':port\'', array(':host' => $server['host'], ':port' => $server['port']));
			//}

		}

		// Setup the flags
		$this->_flags = Arr::get($this->_config, 'compression', FALSE) ? Redis_COMPRESSED : FALSE;
	}

	/**
	 * Retrieve a cached value entry by id.
	 *
	 *     // Retrieve cache entry from Redis group
	 *     $data = Cache::instance('Redis')->get('foo');
	 *
	 *     // Retrieve cache entry from Redis group and return 'bar' if miss
	 *     $data = Cache::instance('Redis')->get('foo', 'bar');
	 *
	 * @param   string  $id       id of cache to entry
	 * @param   string  $default  default value to return if cache miss
	 * @return  mixed
	 * @throws  Cache_Exception
	 */
	public function get($id, $default = NULL)
	{
		// Get the value from Redis
		$value = $this->_Redis->get($this->_sanitize_id($id));

		// If the value wasn't found, normalise it
		if ($value === FALSE)
		{
			$value = (NULL === $default) ? NULL : $default;
		}

		// Return the value
		return $value;
	}

	/**
	 * Set a value to cache with id and lifetime
	 *
	 *     $data = 'bar';
	 *
	 *     // Set 'bar' to 'foo' in Redis group for 10 minutes
	 *     if (Cache::instance('Redis')->set('foo', $data, 600))
	 *     {
	 *          // Cache was set successfully
	 *          return
	 *     }
	 *
	 * @param   string   $id        id of cache entry
	 * @param   mixed    $data      data to set to cache
	 * @param   integer  $lifetime  lifetime in seconds, maximum value 2592000
	 * @return  boolean
	 */
	public function set($id, $data, $lifetime = 3600)
	{
		// If the lifetime is greater than the ceiling
		if ($lifetime > Cache_Redis::CACHE_CEILING)
		{
			// Set the lifetime to maximum cache time
			$lifetime = Cache_Redis::CACHE_CEILING + time();
		}
		// Else if the lifetime is greater than zero
		elseif ($lifetime > 0)
		{
			$lifetime += time();
		}
		// Else
		else
		{
			// Normalise the lifetime
			$lifetime = 0;
		}

		// Set the data to Redis
		return $this->_Redis->set($this->_sanitize_id($id), $data, $lifetime);
	}

	/**
	 * Delete a cache entry based on id
	 *
	 *     // Delete the 'foo' cache entry immediately
	 *     Cache::instance('Redis')->delete('foo');
	 *
	 *     // Delete the 'bar' cache entry after 30 seconds
	 *     Cache::instance('Redis')->delete('bar', 30);
	 *
	 * @param   string   $id       id of entry to delete
	 * @param   integer  $timeout  timeout of entry, if zero item is deleted immediately, otherwise the item will delete after the specified value in seconds
	 * @return  boolean
	 */
	public function delete($id, $timeout = 0)
	{
		// Delete the id
		return $this->_Redis->delete($this->_sanitize_id($id), $timeout);
	}

	/**
	 * Delete all cache entries.
	 *
	 * Beware of using this method when
	 * using shared memory cache systems, as it will wipe every
	 * entry within the system for all clients.
	 *
	 *     // Delete all cache entries in the default group
	 *     Cache::instance('Redis')->delete_all();
	 *
	 * @return  boolean
	 */
	public function delete_all()
	{
		$result = $this->_Redis->flush();

		// We must sleep after flushing, or overwriting will not work!
		// @see http://php.net/manual/en/function.Redis-flush.php#81420
		sleep(1);

		return $result;
	}

	/**
	 * Callback method for Redis::failure_callback to use if any Redis call
	 * on a particular server fails. This method switches off that instance of the
	 * server if the configuration setting `instant_death` is set to `TRUE`.
	 *
	 * @param   string   $hostname
	 * @param   integer  $port
	 * @return  void|boolean
	 * @since   3.0.8
	 */
	public function _failed_request($hostname, $port)
	{
		if ( ! $this->_config['instant_death'])
			return;

		// Setup non-existent host
		$host = FALSE;

		// Get host settings from configuration
		foreach ($this->_config['servers'] as $server)
		{
			// Merge the defaults, since they won't always be set
			$server += $this->_default_config;
			// We're looking at the failed server
			if ($hostname == $server['host'] and $port == $server['port'])
			{
				// Server to disable, since it failed
				$host = $server;
				continue;
			}
		}

		if ( ! $host)
			return;
		else
		{
			return $this->_Redis->setServerParams(
				$host['host'],
				$host['port'],
				$host['timeout'],
				$host['retry_interval'],
				FALSE, // Server is offline
				array($this, '_failed_request'
				));
		}
	}

	/**
	 * Increments a given value by the step value supplied.
	 * Useful for shared counters and other persistent integer based
	 * tracking.
	 *
	 * @param   string    id of cache entry to increment
	 * @param   int       step value to increment by
	 * @return  integer
	 * @return  boolean
	 */
	public function increment($id, $step = 1)
	{
		return $this->_Redis->increment($id, $step);
	}

	/**
	 * Decrements a given value by the step value supplied.
	 * Useful for shared counters and other persistent integer based
	 * tracking.
	 *
	 * @param   string    id of cache entry to decrement
	 * @param   int       step value to decrement by
	 * @return  integer
	 * @return  boolean
	 */
	public function decrement($id, $step = 1)
	{
		return $this->_Redis->decrement($id, $step);
	}
}