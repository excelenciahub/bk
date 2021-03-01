function showMessage(message,alert,location=''){
    
    if(location==''){
        $("#message #inner-message").addClass('alert-'+alert);
        $("#message #inner-message").html(message);
        $("#message").fadeIn('slow', function() {
            setTimeout(function(){
                $("#message").fadeOut('slow', function() {
                    $("#message #inner-message").html('');
                    $("#message #inner-message").attr('class','alert fade in');
                });
            },2500);
        });
    }
    else{
        $(location+' .inner-message').addClass('alert alert-'+alert);
        var html = '';
        for(var i=0;i<message.length;i++){
            html += '<p>* '+message[i]+'</p>';
        }
        //$(location+' .inner-message').html(html);
        $(location+' .inner-message').html(message);
        $(location).fadeIn('slow', function() { });
        setTimeout(function(){
            $(location).fadeOut('slow', function() {
                $(location+" .inner-message").html('');
                $(location+".inner-message").attr('class','inner-message fade in alert-dismissable font-verdana');
            });
        },2500);
    }
}

$(document).ready(function () {
    $('.sidebar-menu').tree();
    $(function(){
        $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
    });

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    $('.chosen-select').chosen();
    $('.overlay').css('display','none');
    
    $(".form-validate").validate({
		// validation rules for registration form
		errorClass: "text-red",
		validClass: "text-green",
		errorElement: 'div',
		rules: {
			radioinput: {
				required: true,
				minlength: 1
			},
			checkboxinput: {
				required: true,
				minlength: 1
			}
		},
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			}
			else if (element.hasClass('ace-switch')) {     
				error.insertAfter(element.next('span'));  // select2
			}
			else if (element.hasClass('ace-file') || element.hasClass('ace-file-2')) {     
				error.insertAfter(element.parent());  // select2
			}
			else if ( (element.attr('type')=='radio' || element.attr('type')=='checkbox') && element.hasClass('ace')) {     
				error.insertAfter(element.parent().parent());  // select2
			}
			else if (element.hasClass('select2')) {     
				error.insertAfter(element.next('span'));  // select2
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
    
    $(".form-validate").submit(function(e){
        $("#message .alert").attr('class','inner-message fade in alert-dismissable');
        $("#message .inner-message").html('');
        if($(".form-validate").validate().errorList.length==0){
            $('.overlay').toggle();
            var url = $(this).attr('action');
            var form = $(this);
            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this), // serializes the form's elements.
                contentType: false,
                cache: false,
                processData:false,
                success: function(data){
                    var response = JSON.parse(data);
                    if(response['status']==1){
                        if($('.form-validate #slug').val()===''){
                            $(form)[0].reset();
                        }
                        $('.chosen-select').trigger("chosen:updated");
                        showMessage(response['message'],'success','#message'); // show response from the php script.
                        $("#refresh_table").trigger("click");
                        $('#myModal').modal('hide');
                    }
                    else{
                        showMessage(response['message'],'danger','#message');
                    }
                    $('.overlay').toggle();                       
                },
                error: function (request, status, error) {
                    showMessage(request.responseText,'warning','#message');
                    $('.overlay').toggle();
                }
            });
        }  
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
});