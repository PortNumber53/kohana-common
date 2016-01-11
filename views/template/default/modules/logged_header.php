<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 10/26/13
 * Time: 9:37 PM
 * Something meaningful about this file
 *
 */

?>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "/service/account/actions",
            type: 'POST',
            dataType: "json",
            data: {
                'logged_in': true
            },
            success: function (response) {

                var html = '';
                for (loop = 0; loop < response.menu.length; loop++) {
                    //html += '<ul role="menu" class="dropdown-menu">';
                    html += '<li class="dropdown"><a href="#" class="dropdown-toogle" data-toggle="dropdown">' + response.menu[loop].label + '<b class="caret"></b></a>';

                    if (response.menu[loop].actions) {
                        html += '<ul class="dropdown-menu">';
                        //var responseArray = JSON.parse(response);
                        for (subloop = 0; subloop < response.menu[loop].actions.length; subloop++) {
                            html += '<li><a href="' + response.menu[loop].actions[subloop].href + '">' + response.menu[loop].actions[subloop].title + '</a></li>';
                        }
                        html += '</ul>';
                    }

                    html += '</li>';
                    //html += ' </ul>';
                }
                $("#user-menu").html(html);
            },
            error: function (response) {
            }
        });

    });
</script>
