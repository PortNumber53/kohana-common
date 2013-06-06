<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 2:12 AM
 *
 */
if (! Route::$cache)
{
	Route::set('account-actions', '<action>(/<request>)',
		array(
			'request'    => '[a-zA-Z0-9_/\-]+',
			'action'     => '(profile|login|signup|logout|reset|forgot|settings)',
		))
		->defaults(array(
			'directory'  => 'Common',
			'controller' => 'Account',
			'action'     => 'profile',
		));

}