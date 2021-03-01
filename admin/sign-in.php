<?php
	require_once("../include/config.php");
	if(isset($_SESSION['admin_id']) && $_SESSION['admin_id']>0){
		header("location:".ADMIN_URL."dashboard.php");exit;
	}
	$error = '';
    $username = '';
    $password = '';
    $remember = '';
	$instance = new admin_master();
    
	if(count($_POST)>0){
		
        $username = isset($_POST['username'])&&$_POST['username']!=''?$instance->db_output($_POST['username']):'';
        $password = isset($_POST['password'])&&$_POST['password']!=''?$instance->db_output($_POST['password']):'';
        $remember = isset($_POST['remember'])&&$_POST['remember']!=''?$instance->db_output($_POST['remember']):'';
        
		$return = $instance->login($_POST);
        
        if($remember==1){
            setcookie('remember', $remember);
            setcookie('username', $username);
            setcookie('password', $password);
        }
        else{
            setcookie('remember', $remember, time()-1000);
            setcookie('username', $username, time()-1000);
            setcookie('password', $password, time()-1000);
        }
        echo $return;exit;
        /*
        if($return->status===0){
            $error = $return->message;
        }
        else{
			header('location:'.ADMIN_URL."dashboard.php");exit;
        }
        */
	}
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Sign In | <?php echo SITE_NAME; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
        <!-- Bootstrap 3.3.7 -->
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_CSS; ?>bootstrap.min.css" />
        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>font-awesome.min.css" />
        <!-- Theme style -->
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_CSS; ?>AdminLTE.css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_CSS; ?>style.css" />
    </head>
    
    <body class="hold-transition login-page">
        <div class="login-box box" style="border: none;">
            <div class="login-logo" style="margin-bottom: 0px;">
                <a href="javascript:void(0);"><?php echo SITE_NAME; ?></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form id="loginform" class="login-validate" action="#" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" id="username" value="<?php echo isset($_COOKIE['username'])?$_COOKIE['username']:$username; ?>" class="form-control" placeholder="User Name" required="true" autofocus="true" />
                        <span class="fa fa-user-circle form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="<?php echo isset($_COOKIE['password'])?$_COOKIE['password']:''; ?>" required="true" />
                        <span class="fa fa-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
    						<div class="inputchkbox">
    							<input id="remember" type="checkbox" name="remember" <?php echo isset($_COOKIE['remember'])&&$_COOKIE['remember']=='1'?'checked="checked"':''; ?> class="chkbox" value="1" />
    							<label for="remember">Remember Me</label>
    						</div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div id="lgnmessage"></div>
            </div>
            <!-- /.login-box-body -->
            <div class="overlay"><i class="fa fa-spinner fa-spin1"></i></div>
        </div>
        <!-- /.login-box -->
    
        <!-- jQuery 3 -->
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>ace/js/chosen.jquery.min.js"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>adminlte.min.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>script.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>validator.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			     $.validator.setDefaults({ ignore: ":hidden:not(select)" }) //for all select
                 $.validator.setDefaults({ ignore: ":hidden:not(.chkbox)" }) //for all select
                 
				$(".login-validate").validate({
					// validation rules for registration form
					errorClass: "text-red",
					validClass: "text-green",
					errorElement: 'div',
					errorPlacement: function(error, element) {
						if(element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						}
						else if (element.hasClass('select2')) {     
							error.insertAfter(element.next('span'));  // select2
						}
                        else if (element.hasClass('chkbox')) {     
							error.insertAfter(element.next('label'));  // chkbox
						}
						else if (element.hasClass('chosen-select')) {     
							//error.insertAfter(element.next('span'));  // chosen-select
							//error.insertAfter("#shop_chosen");
							//element.next("div.chzn-container").append(error);
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						} else {
							error.insertAfter(element);
						}
					},
					onError : function(){
						$('.input-group.error-class').find('.help-block.form-error').each(function() {
							$(this).closest('.form-group').addClass('error-class').append($(this));
						});
					}
				});
                
                $(".login-validate").submit(function(e){
                    $("#lgnmessage").html('');
                    if($(".login-validate").validate().errorList.length==0){
                        $('.overlay').toggle();
                        var url = '<?php echo ADMIN_URL; ?>sign-in.php'
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: new FormData(this), // serializes the form's elements.
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(data){
                                var result = JSON.parse(data);
                                if(result['status']==1){
                                    $('#loginform')[0].reset();
                                    $("#lgnmessage").attr("class","text-green");
                                    $("#lgnmessage").html(result['message']);
                                    setTimeout(function(){
                                        window.location.replace("<?php echo ADMIN_URL; ?>dashboard.php");
                                    },500);
                                }
                                else{
                                    $("#lgnmessage").attr("class","text-red");
                                    $("#lgnmessage").html(result['message']);
                                }
                                $('.overlay').toggle();                       
                            },
                            error: function (request, status, error) {
                                $("#lgnmessage").attr("class","text-red");
                                $("#lgnmessage").html(request.responseText);
                                $('.overlay').toggle();
                            }
                        });
                    }  
                    e.preventDefault(); // avoid to execute the actual submit of the form.
                });
                
			});
		</script>
    </body>

</html>