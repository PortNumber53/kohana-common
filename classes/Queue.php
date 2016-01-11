<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Queue
 */
class Queue
{

    public static $instance;

    private function __construct()
    {

    }

    public static function getInstance($domain)
    {
        if (!isset($instance[$domain])) {
            Queue::$instance[$domain] = new Queue();
        }
        return Queue::$instance[$domain];
    }

    public static function getQueueName($name)
    {
        return 'thumbitt.com:' . Environment::level() . '-' . $name;
    }
}
