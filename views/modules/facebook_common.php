<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/14/13
 * Time: 11:19 PM
 * Something meaningful about this file
 *
 */
$app_id = Kohana::$config->load('facebook.app_id');
?>
<div id="fb-root"></div>
<script>
	window.fbAsyncInit = function() {
		// init the FB JS SDK
		FB.init({
			appId      : '<?php echo $app_id; ?>',                        // App ID from the app dashboard
			status     : true,                                 // Check Facebook Login status
			xfbml      : true                                  // Look for social plugins on the page
		});

		// Additional initialization code such as adding Event Listeners goes here
	};

	// Load the SDK asynchronously
	(function(){
		// If we've already installed the SDK, we're done
		if (document.getElementById('facebook-jssdk')) {return;}

		// Get the first script element, which we'll use to find the parent node
		var firstScriptElement = document.getElementsByTagName('script')[0];

		// Create a new script element and set its id
		var facebookJS = document.createElement('script');
		facebookJS.id = 'facebook-jssdk';

		// Set the new script's source to the source of the Facebook JS SDK
		facebookJS.src = '//connect.facebook.net/en_US/all.js';

		// Insert the Facebook JS SDK into the DOM
		firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
	}());
</script>