<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 4:54 PM
 * Something meaningful about this file
 *
 */

return array(
	'site_name' => 'Core Sample Redesign - 2013',
	'template' => array(
		'selected' => 'default',

		'default' => array(
			'frontend' => array(

				'sections' => array(
					array('name'=>'header', 'view'=>'modules/header',),
					array('name'=>'footer', 'view'=>'modules/footer',),
				),
			),
			'backend' => array(),
		),
	),
);
