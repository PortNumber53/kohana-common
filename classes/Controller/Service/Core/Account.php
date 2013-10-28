<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 8:20 PM
 * Something meaningful about this file
 *
 */

class Controller_Service_Core_Account extends Controller_Service_Core_Service
{

	public function action_actions()
	{
		echo "do an ajax call";
	}

	public function action_ajax_actions()
	{
		$this->output = array(
			'menu' => array(
				array(
					'title' => __('Edit Profile'),
					'href'  => URL::Site(Route::get('account-actions')->uri(array('action'=>'profile', )), TRUE),
				),
				array(
					'title' => __('Your Rewards'),
					'href'  => URL::Site(Route::get('default')->uri(array('controller'=>'reward', 'action'=>'browse', )), TRUE),
				),
				array(
					'title' => __('Logout'),
					'href'  => URL::Site(Route::get('account-actions')->uri(array('action'=>'logout', )), TRUE),
				),
			),
		);

	}
}