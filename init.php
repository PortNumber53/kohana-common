<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/15/13
 * Time: 2:12 AM
 *
 */
if (!Route::$cache) {
    Route::set('backend-actions', 'backend(/<controller>(/<action>(/<request>)))', array(
        'request' => '[a-zA-Z0-9_/\-]+',
    ))->defaults(array(
        'directory' => 'Backend',
        'controller' => 'Dashboard',
        'action' => 'Main',
    ));
    /*
	Route::set('dashboard', 'dashboard',
		array(
			'request'    => '[a-zA-Z0-9_/\-]+',
		))
		->defaults(array(
			'directory'  => 'Backend',
			'controller' => 'Dashboard',
			'action'     => 'Main',
		));
    */

    Route::set('service-actions', 'service(/<controller>(/<action>(/<request>)))', array(
        'request' => '[a-zA-Z0-9_/\-]+',
    ))->defaults(array(
        'directory' => 'Service',
        'controller' => 'Check',
        'action' => 'Unique',
    ));

    Route::set('account-actions', '<action>(/<request>)', array(
        'request' => '[a-zA-Z0-9_/\-]+',
        'action' => '(profile|login|signup|logout|reset|forgot|settings|public)',
    ))->defaults(array(
        'controller' => 'Account',
        'action' => 'profile',
    ));

    Route::set('html-content', '(<request>.<type>(<override>))', array(
        'request' => '[a-zA-Z0-9_/\-]+',
        'type' => '(html|shtml)',
        'override' => '(:edit)',
    ))->filter(function ($route, $params, $request) {
        // Prefix the method to the action name
        if (!empty($params['override']) && $params['override'] == ':edit') {
            $params['action'] = 'edit';
            $params['directory'] = 'Backend';
        }
        //$params['action'] = strtolower($request->method()).'_'.$params['action'];
        return $params; // Returning an array will replace the parameters
    })->defaults(array(
        'controller' => 'Content',
        'action' => 'view',
        'request' => '/',
        'type' => 'html',
    ));

    Route::set('image-actions', 'image/(<action>/<width>/<height>/<method>/)<pictureid>-<request>.<type>', array(
        'action' => '[a-zA-Z_/\-]+',
        'width' => '[0-9]+',
        'height' => '[0-9]+',
        'method' => '(crop)',
        'pictureid' => '[0-9]+',
        'request' => '[a-zA-Z0-9_/\-]+',
        'type' => '(jpg|jpeg|png|gif)',
    ))->defaults(array(
        'controller' => 'Dynimage',
        'action' => 'view',
    ));

    Route::set('sitemap', 'sitemap/<name>(:<page>).<format>', array(
        'name' => '[a-zA-Z0-9_/\-]+',
        'page' => '([0-9]+|count)',
        'format' => '(xml|txt)',
    ))->defaults(array(
        'controller' => 'Sitemap',
        'action' => 'generate',
    ));

    Route::set('seo-robots', 'robots.txt')->defaults(array(
        'controller' => 'Robots',
        'action' => 'index',
    ));


    //Legacy actions
    Route::set('blog-actions', 'gallery(/<id>(-(<slug>)))(:<page>)', array(
        'id' => '[0-9]+',
        'page' => '[0-9]+',
        'slug' => '[a-z0-9_\-\']+',
    ))->defaults(array(
        'controller' => 'Blog',
        'format' => 'Blog',
        'page' => 1,
        'action' => 'view',
    ));

}
