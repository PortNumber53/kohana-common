<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 5/14/13
 * Time: 2:43 AM
 *
 */

?><!DOCTYPE html>
<html lang="en">

<head>
	<meta chartset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo Website::get('site_name', '{config::site_name}'); ?></title>

	<?php Masher::instance('top_head')->add_css('3.0.0/bootstrap.min.css'); ?>
	<?php Masher::instance('top_head')->add_css('/template/default/css/style.css'); ?>
	<?php echo Masher::instance('top_head')->mark_css(); ?>

	<?php Masher::instance('top_head')->add_js('jquery-2.0.3.min.js'); ?>
	<?php echo Masher::instance('top_head')->mark_js(); ?>

</head>

<body>
<?php
echo empty($template['header']) ? '{template.header}' : View::factory($template['header'])->render();
?>

<ol class="breadcrumb">
	<li><a href="/">Home</a></li>
</ol>

<?php
	echo empty($main) ? '{no $main content}' : View::factory($main)->render();
?>


<?php
//echo View::factory('modules/akm2_gravity_points_code_pen')->render();
?>

<?php
echo empty($template['footer']) ? '{template.footer}' : View::factory($template['footer'])->render();
?>

<?php Masher::instance('bottom_body')->add_js('jquery.form.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('3.0.0/bootstrap.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('angular-sanitize-1.1.5.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('underscore-1.5.2.min.js'); ?>
<?php Masher::instance('bottom_body')->add_js('json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.json2html.js'); ?>
<?php Masher::instance('bottom_body')->add_js('jquery.cookie.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/common.js'); ?>
<?php Masher::instance('bottom_body')->add_js('/script/app.js'); ?>
<?php echo Masher::instance('bottom_body')->mark_js(); ?>

<script type="text/template" id="feedback">
	<div class="alert">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
		<ul id="errorlist"></ul>
	</div>
</script>


<script>
	$(function() {
		// Setup drop down menu
		$('.dropdown-toggle').dropdown();
		// Fix input element click problem
		$('.dropdown input, .dropdown label').click(function(e) {
			e.stopPropagation();
		});
	});

	$(document).ready(function() {
		// bind form using ajaxForm
		$('.json-form').ajaxForm({
			// dataType identifies the expected content type of the server response
			dataType:  "json",
			type: "POST",
			// success identifies the function to invoke when the server response
			// has been received
			success:  function (response, statusText, xhr, $form) {
				if (response.error == false) {
					if (response.redirect_url) {
						history.pushState({id: 'updated product info'}, '', response.redirect_url);
						$form.attr("action", response.redirect_url);
						//document.location = response.redirect_url;
					}
					if (response.message) {
						var template = $("#feedback").html();
						$(".form-feedback").html(template);

						var transform = {'tag':'li','html':'${message}'};
						var data = {
							message: response.message
						};
						$(".form-feedback > .alert").removeClass("alert-warning alert-danger").addClass("alert-success");
						$('#errorlist').json2html(data, transform);
						if (response.dismiss_timer) {
							setTimeout('$(".alert").alert("close")', response.dismiss_timer*1000);
						}
					}
				} else {
					var template = $("#feedback").html();
					$(".form-feedback").html(template);

					var transform = {'tag':'li','html':'${message}'};
					var data = {
						message: response.error.message
					};
					$(".form-feedback > .alert").removeClass("alert-warning alert-success").addClass("alert-danger");
					$('#errorlist').json2html(data, transform);
				}
			},
			error: function (response) {
				console.log(response);
			}
		});

		$(".social-action").on("click", "li > a.share", function () {
			var network = $(this).data("network");
			var what = $(this).parent().parent().data('what');
			$.ajax({
				url: "/service/social/share/",
				type: 'POST',
				dataType: "json",
				data: {
					'logged_in': true,
					'what': what
				},
				success: function (response) {
				},
				error: function (response) {
				}
			});
		});

		$(".btn-action").on("click", function() {
			var serviceUrl = $(this).data("service");
			var what = $(this).data("what");
			var amount = $("#amount_"+what).val();
			$.ajax({
				url: serviceUrl,
				type: 'POST',
				dataType: "json",
				data: {
					'logged_in': true,
					'what': what,
					'amount': amount
				},
				success: function (response) {
				},
				error: function (response) {
				}
			});
		});
		$(".btn-grab").on("click", function() {
			var serviceUrl = $(this).data("service");
			var what = $(this).data("what");
			var businessId = $(this).data("business-id");
			var promotionId = $(this).data("promotion-id");
			var amount = $(this).data("amount");
			$.ajax({
				url: serviceUrl,
				type: 'POST',
				dataType: "json",
				data: {
					'logged_in': true,
					'what': what,
					'business_id': businessId,
					'promotion_id': promotionId,
					'amount': amount
				},
				success: function (response) {
				},
				error: function (response) {
				}
			});
		});

		$("#tableData").on("click", "button.btn-delete", function(e) {
			e.stopPropagation();
			var objectId = $(this).data("object-id");
			var url = $(this).data("delete-link");
			$.ajax({
				url: url,
				type: 'POST',
				dataType: "json",
				data: {
					'what': objectId,
					'back_url': encodeURI(document.location)
				},
				success: function (response) {
					if (response.error == false) {
						if (response.redirect_url) {
							//document.location = decodeURI(response.redirect_url);
						}
					}
					if (response.table_body) {
						$("#tableData > tbody").html(response.table_body);
					}
				},
				error: function (response) {
				}
			});
		}).on("click", ".clickable", function(e) {
			e.preventDefault();
			var url = $(this).data("edit-link");
			document.cookie = "back_url="+encodeURI(document.location);
			document.location = url;
		});



		//Check back_URL
		var cookie_back = $.cookie("back_url");
		if (cookie_back != undefined) {
			$(".back-link").attr("href", cookie_back).removeClass("hidden");
		}
	});
</script>

</body>
</html>
