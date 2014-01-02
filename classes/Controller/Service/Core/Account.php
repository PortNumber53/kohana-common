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
		$profile = Account::profile();
		if ($profile['profile'] === 'ADMIN')
		{
			$this->output = array('menu' => Website::get('menu', array()));

		}
		else
		{
			$this->output = array('menu' => array(),);
		}
	}
}