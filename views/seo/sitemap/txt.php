<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 12/27/13
 * Time: 1:51 AM
 * Something meaningful about this file
 *
 */

foreach ($data as $item) {
	$line = URL::Site($item['url'], TRUE) . '/';
	echo "$line\n";
}
