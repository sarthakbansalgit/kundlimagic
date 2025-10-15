        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Welcome to Bada Engineering</title>
        <link href="<?php echo base_url(); ?>admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>admin/assets/css/login_custom.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>admin/assets/js/modernizr.min.js"></script>
        <style>
        .account-pages
        {
        background: url(../assest/image/gallary_bg3.jpg);
        position: absolute;
        height: 100%;
        width: 100%;
        background-size: 100% 100%;
        }
        .card-box{
        background-color: #ffffff69!important;
        }
        .text-dark {
        color: #ffffff !important;
        }
        .modal-dialog {
    width: 417px!important;
    margin: 30px auto;
}
      </style>

        </head>
        <body>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
        <div class=" card-box card_box_custom">
        <div class="panel-heading">
        <h3 class="text-center"> <img style="width: 100%;" src="<?php echo base_url(); ?>assest/image/logo.png" class="logo_head"/></h3>
        </div>
        <div class="panel-body">
        <?php
        if($this->session->flashdata('error'))
        {
                echo "<div class='alert alert-danger'>".$this->session->flashdata('error')."</div>";
        }
        if($this->session->flashdata('success'))
        {
                echo "<div class='alert alert-success'>".$this->session->flashdata('success')."</div>";
        }
        ?>
        <?php echo form_open(base_url( 'admin/Login' ),  array( 'method' => 'post', 'class' => 'form-horizontal','id'=>"loginForm" ));?>
        <div class="form-group ">
        <div class="col-xs-12">
        <input class="form-control name_validation email_validation required_validation_for_login_customer" type="text"  name="email"  placeholder="Email Address" >
        </div>
        </div>
        <div class="form-group">
        <div class="col-xs-12">
        <input class="form-control name_validation required_validation_for_login_customer" type="password" name="password"  placeholder="Password" >
        </div>
        </div>
        <div class="form-group text-center m-t-40">
        <div class="col-xs-12">
        <input type="submit" class="btn btn_theme_dark text-uppercase" name="vendorLogin" value="Log In"/>
        </div>
        </div>
        <div class="form-group m-t-30 m-b-0">
        <div class="col-sm-12">
        <div style="cursor: pointer;" class="text-dark" data-toggle="modal" data-target="#send"><i class="fa fa-lock m-r-5"></i> Forgot your password?</div>
        </div>

        </div>
        <?php echo form_close();?>
        </div>
        </div>
        </div>

        <!---Start Forget -->
        <div id="send" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
        <form method="post" id="forgetFormDataValue">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Forget Password</h4>
        </div>
        <div class="modal-body">
        <div class="row setErrorDataValue"></div>
        <div class="row">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        <div class="col-md-12 form-horizontal">
        <div class="form-group">
        <label class="col-md-3 control-label">Email</label>
        <div class="col-md-9">
        <input type="text" class="form-control required_validation_for_forget_customer name_validation email_validation" name="checkEmail" placeholder="Enter your email">
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info setForgetSubmit">Send</button/>
        </div>
        </div>
        </form>
        </div>
        </div><!-- End Forget -->
        <!--script-->
        <script>
        var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/detect.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/fastclick.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/wow.min.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/jquery.app.js"></script>
        <script src="<?php echo base_url(); ?>admin/assets/js/validation.js"></script>
        <!-- CLient side form validation -->
        <script type="text/javascript">
	$(document).ready(function(){
		$(document).on('submit','#forgetFormDataValue',function(e){
			var check_required_field='';
			$(".required_validation_for_forget_customer").each(function(){
				var val22 = $(this).val();
				if (!val22){
					check_required_field =$(this).size();
					$(this).css("border-color","#ccc");
					$(this).css("border-color","red");
				}
				$(this).on('keypress change',function(){
					$(this).css("border-color","#ccc");
				});
			});
			if(check_required_field)
			{
				return false;
			}
			else {
                                //return true;
                                e.preventDefault();
                                var formData = $('#forgetFormDataValue').serialize();
                                $('.setForgetSubmit').attr('disabled',true);
                                jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url(); ?>" + "Login/forgetCheckData",
                                data: formData,
                                success: function(data)
                                {
                                        if($.trim(data) == 'all')
                                        {
                                                $('.setErrorDataValue').html('');
                                                $('.setErrorDataValue').html('<div class="alert alert-danger">All fields are mandatory</div>');
                                                window.setTimeout(function(){
                                                        window.location.href='<?php echo base_url() ?>';
                                                });
                                        }
                                        else if($.trim(data) == 'no')
                                        {
                                                $('.setErrorDataValue').html('');
                                                $('.setErrorDataValue').html('<div class="alert alert-danger">Something went wrong. Please try again</div>');
                                                window.setTimeout(function(){
                                                        window.location.href='<?php echo base_url() ?>';
                                                });
                                        }
                                        else if($.trim(data) == 'email')
                                        {
                                                $('.setErrorDataValue').html('');
                                                $('.setErrorDataValue').html('<div class="alert alert-danger">Please enter correct email address</div>');
                                                window.setTimeout(function(){
                                                        window.location.href='<?php echo base_url() ?>';
                                                });
                                        }
                                        else if($.trim(data) == 'yes')
                                        {
                                                $('.setErrorDataValue').html('');
                                                window.setTimeout(function(){
                                                        window.location.href='<?php echo base_url() ?>forget';
                                                });
                                        }
                                        else
                                        {
                                                $('.setErrorDataValue').html('');
                                                $('.setErrorDataValue').html('<div class="alert alert-danger">Something went wrong. Please try again</div>');
                                                window.setTimeout(function(){
                                                        window.location.href='<?php echo base_url() ?>';
                                                });
                                        }
                                }
                                });
			}
		});
	});
	</script>
        <script type="text/javascript">
	$(document).ready(function(){
		$(document).on('submit','#loginForm',function(){
			var check_required_field='';
			$(".required_validation_for_login_customer").each(function(){
				var val22 = $(this).val();
				if (!val22){
					check_required_field =$(this).size();
					$(this).css("border-color","#ccc");
					$(this).css("border-color","red");
				}
				$(this).on('keypress change',function(){
					$(this).css("border-color","#ccc");
				});
			});
			if(check_required_field)
			{
				return false;
			}
			else {
				return true;
			}
		});
	});
	</script>
        </body>
        </html>
