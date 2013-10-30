<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 2:12 AM
 *
 */
if (! Route::$cache)
{
	Route::set('service-actions', 'service(/<controller>(/<action>(/<request>)))',
		array(
			'request'    => '[a-zA-Z0-9_/\-]+',
		))
		->defaults(array(
			'directory'  => 'Service',
			'controller' => 'Check',
			'action'     => 'Unique',
		));

	Route::set('account-actions', '<action>(/<request>)',
		array(
			'request'    => '[a-zA-Z0-9_/\-]+',
			'action'     => '(profile|login|signup|logout|reset|forgot|settings|public)',
		))
		->defaults(array(
			'directory'  => 'Common',
			'controller' => 'Account',
			'action'     => 'profile',
		));

	Route::set('html-content', '(<request>.<type>)(<override>)',
		array(
			'request'       => '[a-zA-Z0-9_/\-]+',
			'type'          => '(html|shtml)',
			'override'      => '(:edit)',
		))->filter(function($route, $params, $request)
		{
			// Prefix the method to the action name
			if ( ! empty($params['override']) && $params['override'] == ':edit')
			{
				$params['action'] = 'edit';
				$params['directory'] = 'Backend';
			}
			//$params['action'] = strtolower($request->method()).'_'.$params['action'];
			return $params; // Returning an array will replace the parameters
		})
		->defaults(array(
			'directory'  => 'Common',
			'controller' => 'Content',
			'action'     => 'view',
			'request'    => '/',
			'type'       => 'html',
		));
}