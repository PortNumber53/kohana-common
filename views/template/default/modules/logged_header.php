<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 9:37 PM
 * Something meaningful about this file
 *
 */

?>
<a href="#" class="dropdown-toogle" data-toggle="dropdown" id="accountActions">Profile<b class="caret"></b></a>

<script>
	$(document).ready(function() {
		$.ajax({
			url: "/service/account/actions",
			type: 'POST',
			dataType: "json",
			data: {
				'logged_in': true
			},
			success: function (response) {

				var html = '<ul role="menu" class="dropdown-menu">';
				if (response.menu) {
					//var responseArray = JSON.parse(response);
					for (loop = 0; loop < response.menu.length; loop++) {
						html += '<li><a href="' + response.menu[loop].href+ '">' + response.menu[loop].title+ '</a></li>';
					}
				}
				html += '</ul>';
				$("#accountActions").after( html );
			},
			error: function (response) {
			}
		});

	});
</script>
