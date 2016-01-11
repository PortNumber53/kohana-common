<?php defined('SYSPATH') or die('No direct script access.');
?>
<div id="forgot-page" class="container">
    <div class="bg">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="title text-center">Recover acccess to your account</h2>


                <div class="ajax-form-container"><!--forgot form-->
                    <h2>Forgot your password?</h2>

                    <form class="ajax-form" id="form_recover" method="post"
                          action="<?php echo URL::site(Route::get('account-actions')->uri(array('action' => 'forgot',)),
                              true) ?>">
                        <input type="email" name="email" id="email" placeholder="e-mail" value=""/>

                        <div id="form-feedback" class="alert alert-success" role="alert" style="display: none">...</div>


                        <button type="button" class="btn btn-default form-action" id="btn_login" name="btn_form">Reset
                            password
                        </button>
                    </form>
                </div>
                <!--/forgot form-->

            </div>

        </div>
    </div>
</div><!--/#forgot- -->


<script>
    $(document).ready(function () {
        $("#form_recover").on('click', '.form-action', function (e) {
            e.preventDefault();

            var data = $("#form_recover").serialize(),

                xhr = $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: data,
                    dataType: "JSON"
                });

            xhr.success(function (data) {
                if (data.hasOwnProperty("errorCode")) {
                    switch (data.errorCode) {
                        case 0:
                            $("#email").val("").hide();
                            $("#btn_login").hide();
                            $("#form-feedback").html("We are sending you an email with instructions, please check your inbox").show();
                            break;
                    }
                }
            });

            xhr.error(function (data) {
                console.log(data, 'FAIL');
            });

            return false;
        })
    });
</script>
