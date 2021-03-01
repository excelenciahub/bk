<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

<!-- Main content -->
<section class="content">
    <?php require_once(DIR_INCLUDES.'alerts.php'); ?>
    <?php
        if($action=='add'||$action=='edit'){
            ?>
            <form action="<?php echo CURRENT_URL; ?>" class="form-validate" method="post">
                <!-- Default box -->
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ucfirst($action); ?> Order</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <span class="dropdown">
                            	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            	<ul class="dropdown-menu dropdown-menu-right">
                            		<li><a href="<?php echo CURRENT_URL; ?>" class="text-olive"><i class="fa fa-eye"></i> View Orders</a></li>
                            	</ul>
                            </span>
                        </div>
                    </div>
                    <div class="box-body order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User <span class="text-red star">*</span></label>
                                    <select class="form-control chosen-select" name="user_id" id="user_id">
                                        <option value="">Select User</option>
                                        <?php
                                            foreach($users as $key=>$val){
                                                ?>
                                                <option <?php echo $user_id==$val->id?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->first_name.' '.$val->last_name.' ('.$val->city_name.')'; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label>Payment Type <span class="text-red star">*</span></label>
                                <div class="form-group">
                                    <div class="inputchkbox">
                                        <input type="checkbox" class="payment_type" name="payment_type" id="payment_type_1" value="1" <?php echo $payment_type=='1'?'checked="checked"':''; ?> /><label for="payment_type_1">Instant</label>
                                        <input type="checkbox" class="payment_type" name="payment_type" id="payment_type_2" value="2" <?php echo $payment_type=='2'?'checked="checked"':''; ?> /><label for="payment_type_2">Future</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 payment_date_box" id="payment_date_box_0" style="<?php echo $payment_type=='2'?'display: block;':''; ?>">
                                <div class="form-group">
                                    <label>Payment Date <span class="text-red star">*</span></label>
                                    <input type="text" name="payment_date" id="payment_date" class="form-control input-sm payment_date datepicker" readonly="true" value="<?php echo $payment_date; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="product_box box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Product Detail</h3>
                                <div class="box-tools pull-right" style="display: none;">
                                    <button type="button" class="btn btn-box-tool remove_box" title="Remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row product_row">
                                    <div class="col-md-2 col-sm-3">
                                        <div class="form-group">
                                            <label>Category <span class="text-red star">*</span></label>
                                            <select class="form-control chosen-select category_id" name="products[0][category_id]" id="category_id">
                                                <option value="">Select Category</option>
                                                <?php
                                                    foreach($categories as $key=>$val){
                                                        ?>
                                                        <option <?php echo isset($products[0]['category_id'])&&$products[0]['category_id']==$val->id?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3">
                                        <div class="form-group">
                                            <input type="hidden" class="pro_id" value="<?php echo isset($products[0]['product_id'])?$products[0]['product_id']:''; ?>" />
                                            <label>Product <span class="text-red star">*</span></label>
                                            <select class="form-control chosen-select product_id" name="products[0][product_id]" id="product_id">
                                                <option value="">Select Product</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 col-sm-3">
                                        <div class="form-group">
                                            <label>QTY <span class="text-red star">*</span></label>
                                            <input type="number" name="products[0][qty]" id="qty" class="form-control qty" value="<?php echo isset($products[0]['qty'])?$products[0]['qty']:''; ?>" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 col-sm-3">
                                        <div id="payable_amount_0" class="text-green payable_amount"><?php echo isset($products[0]['payable_amount'])!=''?'<div class="form-group"><label class="form-label">Payable Amount:</label><br /><i class="fa fa-inr"></i> '.$products[0]['payable_amount'].'</div>':''; ?></div>
                                    </div>
                                </div>
                                <div class="product_price"></div>
                            </div>
                        </div>
                        
                        <?php
                            foreach($products as $key=>$val){
                                if($key>0){
                                    ?>
                                    <div class="product_box box box-default box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Product Detail</h3>
                                            <div class="box-tools pull-right" style="display: none;">
                                                <button type="button" class="btn btn-box-tool remove_box" title="Remove"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="row product_row">
                                                <div class="col-md-2 col-sm-3">
                                                    <div class="form-group">
                                                        <label>Category <span class="text-red star">*</span></label>
                                                        <select class="form-control chosen-select category_id" name="products[<?php echo $key; ?>][category_id]" id="category_id<?php echo $key; ?>">
                                                            <option value="">Select Category</option>
                                                            <?php
                                                                foreach($categories as $k=>$v){
                                                                    ?>
                                                                    <option <?php echo $val['category_id']==$v->id?'selected="selected"':''; ?> value="<?php echo $v->id; ?>"><?php echo $v->name; ?></option>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-3">
                                                    <div class="form-group">
                                                        <input type="hidden" class="pro_id" value="<?php echo $val['product_id']; ?>" />
                                                        <label>Product <span class="text-red star">*</span></label>
                                                        <select class="form-control chosen-select product_id" name="products[<?php echo $key; ?>][product_id]" id="product_id<?php echo $key; ?>">
                                                            <option value="">Select Product</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2 col-sm-3">
                                                    <div class="form-group">
                                                        <label>QTY <span class="text-red star">*</span></label>
                                                        <input type="number" name="products[<?php echo $key; ?>][qty]" id="qty<?php echo $key; ?>" class="form-control qty" value="<?php echo $val['qty']; ?>" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2 col-sm-3">
                                                    <div id="payable_amount_<?php echo $key; ?>" class="text-green payable_amount"><?php echo $val['payable_amount']!=''?'<div class="form-group"><label class="form-label">Payable Amount:</label><br /><i class="fa fa-inr"></i> '.$val['payable_amount'].'</div>':''; ?></div>
                                                </div>
                                            </div>
                                            <div class="product_price"></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                        
                        <div id="more_product"></div>
                        <div class="form-group">
                            <button type="button" id="add_product" class="btn btn-default"><i class="fa fa-plus"></i></button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" id="note" class="form-control"><?php echo $note; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div id="total_amount"></div>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
                    <div class="box-footer">
                        <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice; ?>" />
                        <input type="hidden" name="action" id="action" value="save" />
                        <button type="submit" class="btn btn-default btn-sm text-olive"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
                <!-- /.box -->
            </form>
            
            
            
            <link type="text/css" href="<?php echo ADMIN_PLUGINS; ?>datetimepicker/datetimepicker.css" rel="stylesheet" />
            <script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datetimepicker/datetimepicker.js"></script>
            <style type="text/css">
                .payment_date_box{
                    display: none;
                }
                .product_row{
                
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function(){
                    var product = <?php echo isset($products)?count($products):0; ?>;
                    var clone = $(".product_box:first").clone(true);
                    $("#add_product").click(function(){
                        var id = $(this).val();
                        product++;
                        clone.clone(true).find("input.form-control,select").each(function () {    
                            $(this).val("");
                            var name = $(this).attr("name");
                            var new_name = name.replace("[0]","["+product+"]");
                            $(this).attr("name",new_name);
                            $(this).attr("id",$(this).attr('id')+product);
                        }).end().find(".payment_type").each(function () { 
                            $(this).prop('checked', false);
                            var name = $(this).attr("name");
                            var new_name = name.replace("[0]","["+product+"]");
                            $(this).attr("name",new_name);
                            $(this).attr("id",$(this).attr('id')+product);
                            $(this).next('label').attr('for',$(this).attr('id'));
                        }).end().find('.box-tools').css('display','block').end().find('.chosen-select').chosen().end().prop('id','product_box'+product).insertBefore("#more_product");
                        $('.datepicker').datetimepicker({
                            format:'d-m-Y',
                            timepicker: false,
                            scrollInput:false,
                            allowBlank:true,
                            minDate: 0,
                        });
                        $(".remove_box").click(function(){
                            $(this).closest(".box").fadeOut(300, function(){
                                $(this).remove();
                            });
                        })
                    });
                    
                    var product_detail = [];
                    $(".fancybox").fancybox({
                		openEffect	: 'none',
                		closeEffect	: 'none'
                	});
                    
                    $(document).delegate(".category_id",'change',function(){
                        
                        $(this).closest('.row').find(".product_price").html('');
                        $(".overlay").css("display","block");
                        var data = {
                            'category_id': $(this).val(),
                            'action': 'cat_products'
                        };
                        var otherInput = $(this).closest('.row').find('.product_id');
                        var p = $(this).closest('.product_row').find('.pro_id').val();
                        $.ajax({
                            url: "<?php echo ADMIN_URL; ?>products.php",
                            type: "post",
                            data: data,
                            success: function(data) {
                                $(otherInput).find('option').not(':first').remove();
                                data = JSON.parse(data);
                                //console.log(data);
                                $.each(data['records'],function (key, val){
                                    product_detail[val['id']] = val;
                                    var s = '';
                                    if(val['id']==p){
                                        s = 'selected="selected"';
                                    }
                                    $(otherInput)
                                     .append($("<option "+s+"></option>")
                                                .attr("value",val['id'])
                                                .text(val['name']));
                                                //console.log(product_detail);
                                });
                                $(otherInput).trigger('change');
                                $(otherInput).trigger('chosen:updated');
                                $(".overlay").css("display","none");
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                $(".overlay").css("display","none");
                            }
                        });
                        /*
                        $(this).closest('.box-body').find(".product_price,.payable_amount").html('');
                        $(this).closest('.box-body').find('.qty').val('');
                        $(this).closest('.box-body').find('.payment_type').prop('checked', false);
                        showAmount($(this).closest('.order-body'),product_detail);
                        */
                    });
                    
                    <?php
                        if(count($products)>0){
                            ?>
                            $(".category_id").trigger('change');
                            <?php
                        }
                    ?>
                    $(document).delegate(".product_id",'change',function(){
                        if($(this).val()>0){
                            var html = '<div class="box box-default box-solid">';
                                html += '<div class="box-header with-border"><h3 class="box-title">Product Price</h3></div>';
                                html += '<div class="box-body">';
                                html = '';
                                    html += '<table class="table table-bordered table-striped">';
                                        html += '<tr><th colspan="2" class="text-center">QTY</th><th colspan="2" class="text-center">Price</th></tr>';
                                        html += '<tr><th>Min</th><th>Max</th><th>Instant</th><th>Future</th></tr>'
                                        //html += '<tbody>';
                                            $.each(product_detail[$(this).val()]['price'],function (key, val){
                                                html += '<tr><td>'+val['min_qty']+'</td><td>'+val['max_qty']+'</td><td><i class="fa fa-inr"></i> '+val['instant_price']+'</td><td><i class="fa fa-inr"></i> '+val['future_price']+'</td></tr>';
                                            });
                                        //html += '</tbody>';
                                    html += '</table>';
                                    
                                //html += '</div>'
                            //html += '</box>';
                            //$(this).closest('.box-body').find(".product_price").html(html);
                            $(this).after('<i style="cursor: pointer;" class="fa fa-question-circle text-aqua" data-toggle="popover" data-html="true" title="Price List" data-content=\''+html+'\'></i>');
                            $('[data-toggle="popover"]').popover();
                            $('.popover').css('max-width','500px');
                        }
                        else{
                            $(this).closest('.box-body').find(".product_price,.payable_amount").html('');
                            $(this).closest('.box-body').find('.qty').val('');
                            $(this).closest('.box-body').find('.payment_type').prop('checked', false);
                        }
                        //showAmount($(this).closest('.order-body'),product_detail);
                    });
                    
                    $('.datepicker').datetimepicker({
                        format:'d-m-Y',
                        timepicker: false,
                        scrollInput:false,
                        allowBlank:true,
                        minDate: 0,
                    });
                    
                    $(document).delegate(".payment_type",'click', function(){
                        $(this).closest('.box-body').find('.payment_type').not(this).prop('checked', false);
                        var payment_type = $(this).val();
                        if(payment_type==1){
                            $(this).closest('.box-body').find(".payment_date_box").css("display","none");
                        }
                        else if(payment_type==2){
                            $(this).closest('.box-body').find(".payment_date_box").css("display","block");
                        }
                        showAmount($(this).closest('.order-body'),product_detail);
                    });
                    
                    $(document).delegate(".qty",'keyup',function(){
                        console.log(product_detail);
                        showAmount($(this).closest('.order-body'),product_detail);
                    });
                });
                
                function showAmount(element1,product_detail){
                    var total_amount = 0;
                    $.each($(element1).find('.product_box'),function(key,element){
                        var price = 0;
                        if($(element).find(".product_id").val()!=''){
                            var qty = $(element).find(".qty").val()==''?0:parseFloat($(element).find(".qty").val());
                            var payment_type = $('.payment_type:checked').val()!=undefined?$('.payment_type:checked').val():0;
                            $.each(product_detail[$(element).find(".product_id").val()]['price'],function (key, val){
                                if(qty>=parseFloat(val['min_qty'])&&(qty<=parseFloat(val['max_qty'])||parseFloat(val['max_qty'])==0)){
                                    if(payment_type==1){
                                        price = parseFloat(val['instant_price']);
                                    }
                                    else if(payment_type==2){
                                        price = parseFloat(val['future_price']);
                                    }
                                }
                            });
                            if(price==0){
                                $.each(product_detail[$(element).find(".product_id").val()]['price'],function (key, val){
                                    if(qty>=parseFloat(val['min_qty'])){
                                        if(payment_type==1){
                                            price = parseFloat(val['instant_price']);
                                        }
                                        else if(payment_type==2){
                                            price = parseFloat(val['future_price']);
                                        }
                                    }
                                });
                            }
                        }
                        
                        if(price>0){
                            $(element).find(".payable_amount").html('<div class="form-group"><label class="form-label">Payable Amount:</label><br /><i class="fa fa-inr"></i> '+(price*qty).toFixed(2)+'</div>');
                            total_amount = total_amount+(price*qty);
                        }
                        else{
                            $(element).find(".payable_amount").html('');
                        }
                    });
                    
                    $("#total_amount").html('<div class="form-group text-green"><label class="form-label">Total Amount:</label> <i class="fa fa-inr"></i> '+total_amount.toFixed(2)+'</div>');
                    
                    /*
                    var price = 0;
                    console.log($(element).find(".product_id").val());
                    if($(element).find(".product_id").val()!=''){
                        var qty = $(element).find(".qty").val()==''?0:parseInt($(element).find(".qty").val());
                        var payment_type = $('.payment_type:checked').val()!=undefined?$('.payment_type:checked').val():0;
                        console.log(payment_type);
                        $.each(product_detail[$(element).find(".product_id").val()]['price'],function (key, val){
                            if(qty>=parseInt(val['min_qty'])&&(qty<=parseInt(val['max_qty'])||parseInt(val['max_qty'])==0)){
                                if(payment_type==1){
                                    price = parseInt(val['instant_price']);
                                }
                                else if(payment_type==2){
                                    price = parseInt(val['future_price']);
                                }
                            }
                        });
                    }
                    console.log(price);
                    if(price>0){
                        $(element).find(".payable_amount").html('<div class="form-group"><label class="form-label">Payable Amount:</label><br /><i class="fa fa-inr"></i> '+(price*qty)+'</div>');
                    }
                    else{
                        $(element).find(".payable_amount").html('');
                    }
                    */
                }
                
            </script>
            <?php
        }
        else if($action=='view'){
            ?>
            <!-- Default box -->
            <div class="box box-widget box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Orders</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <span class="dropdown">
                        	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                        	<ul class="dropdown-menu dropdown-menu-right">
                        		<li><a href="<?php echo CURRENT_URL; ?>?action=add"><i class="fa fa-plus"></i> Add Order</a></li>
                        		<li><a href="javascript:void(0);" id="clear_state"><i class="fa fa-refresh"></i> Reset Filter</a></li>
                        	</ul>
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <table id="data-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#NO</th>
                                <th>USER</th>
                                <th>INVOICE</th>
                                <th>TOTAL PRODUCTS</th>
                                <th>TOTAL QTY</th>
                                <th>AMOUNT</th>
                                <th>PAYMENT DATE</th>
                                <th class="text-center">PAYMENT STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
            </div>
            <!-- /.box -->
            
            <!-- DataTables -->
            <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>datatables/css/dataTables.bootstrap.min.css" />
            <script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datatables/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datatables/js/dataTables.bootstrap.min.js"></script>
            
            <script type="text/javascript">
                $(document).ready(function() {
                    
                    var table = $("#data-table").on( 'init.dt', function () {
                        
                        var jsn = JSON.parse('<?php echo json_encode($filters); ?>');
                        $("#data-table").parents("div.row:first").before('<div class="row"><div id="filtercontent"></div></div>');
                        $.each( jsn, function(i, item){
                            var filter = table.state().columns[i].search.search!=undefined?table.state().columns[i].search.search.replace(/^\^+|\$+$/gm,''):'';
                            var select = $('<select id="filer_'+i+'" class="form-control chosen-select"><option value="">All</option></select>')
            					.insertBefore('#filtercontent')
            					.on( 'change', function () {
            						var val = $(this).val();
            						
            						table.column( i )
            							.search( val ? '^'+$(this).val()+'$' : val, true, false )
            							.draw();
            					} );
                            
                            $.each( item.data, function(j, val){
                                var sel = filter!=''&&filter==j?'selected="selected"':'';
                                select.append( '<option '+sel+' value="'+ j +'">'+val+'</option>' );
                            });
                                
                            $('#filer_'+i).wrapAll('<div class="col-sm-2 form-group"></div>');
            				$('<label>'+item.label+'</label>').insertBefore('#filer_'+i);
                            $(select).chosen();
            			});
                        $('#data-table_wrapper').removeClass('form-inline');
                       
                    }).DataTable({
                        "processing": true,
                        "serverSide": true,
                        "stateSave": true,
                        "bStateSave": true,
                        "pageLength": <?php echo RECORD_PER_PAGE; ?>,
                        "oLanguage": {
                            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Please wait...',
                        },
                        "processing" : true,
                        "columnDefs": [
                           { "orderable": false, "targets": [-1] },
                           { "searchable" : false, "targets" : [0,-1] }
                        ],
                        "order": [[ 1, "asc" ]],
                        "ajax": {
                            url: '<?php echo CURRENT_URL; ?>',
                            type: 'GET',
                            "data": {
                                "action": "view"
                            }
                        },
                        "createdRow": function ( row, data, index ) {
                            $('td', row).eq(0).addClass('text-center');
                            $('td', row).eq(7).addClass('text-center');
                            $('td', row).eq(8).addClass('text-center');
                        }
            		});
                    
                    $(".dataTables_length select").chosen();
                    
                    $('#clear_state').click(function(){
                        table.state.clear();
                        window.location.reload();
                    });
                    
                    $(document).delegate('.btn-delete', 'click', function() { 
                        var element = $(this);
                        bootbox.confirm({
                            backdrop: true,
                            title: "Are you sure?",
                            message: "Do you want to delete record now? This cannot be undone.",
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancel',
                                    className: 'btn-default btn-sm'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Confirm',
                                    className: 'btn-warning btn-sm'
                                }
                            },
                            callback: function (result) {
                                if(result===true){
                                    var invoice = $(element).attr('invoice');
                                    var data = 'invoice='+invoice+'&action=delete';
                                    var parent = $(element).parent().parent();
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo CURRENT_URL; ?>',
                                        data: data,
                                        cache: false,
                                        success: function(data){
                                            data = JSON.parse(data);
                                            if(data['status']==1){
                                                parent.fadeOut('slow', function() {
                                                    table.row($(this)).remove().draw(false);
                                                    showMessage(data['message'],'success');
                                                });
                                            }
                                            else{
                                                showMessage(data['message'],'danger');
                                            }
                                        },
                                        error: function (request, status, error) {
                                            showMessage(request.responseText,'warning');
                                        }
                                    });
                                }
                            }
                        });
                    });
                    $(document).delegate('.btn-status', 'click', function() { 
                        var invoice = $(this).attr('invoice');
                        var status = $(this).val();
                        var data = 'invoice='+ invoice +"&status="+status+"&action=status";
                        var button = $(this);
                        $.ajax({
                            type: "POST",
                            url: '<?php echo CURRENT_URL; ?>',
                            data: data,
                            cache: false,
                            success: function(data){
                                data = JSON.parse(data);
                                if(data['status']==1){
                                    $(button).val(Math.abs(status-1));
                                    table.draw(false);
                                    showMessage(data['message'],'success');
                                }
                                else{
                                    button.prop("checked", !button.prop("checked"));
                                    showMessage(data['message'],'danger');
                                }
                            },
                            error: function (request, status, error) {
                                button.prop("checked", !button.prop("checked"));
                                showMessage(request.responseText,'warning');
                            }
                        });
                        
                    });
                    $(document).delegate('.btn-payment-status', 'click', function() {
                        var element = $(this);
                        bootbox.confirm({
                            backdrop: true,
                            title: "Are you sure?",
                            message: "Do you want to delete record now?",
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancel',
                                    className: 'btn-default btn-sm'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Confirm',
                                    className: 'btn-warning btn-sm'
                                }
                            },
                            callback: function (result) {
                                if(result===true){
                                    
                                    var invoice = $(element).attr('invoice');
                                    var status = $(element).val();
                                    var data = 'invoice='+ invoice +"&status="+status+"&action=payment_status";
                                    var button = $(element);
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo CURRENT_URL; ?>',
                                        data: data,
                                        cache: false,
                                        success: function(data){
                                            data = JSON.parse(data);
                                            if(data['status']==1){
                                                $(button).val(Math.abs(status-1));
                                                table.draw(false);
                                                showMessage(data['message'],'success');
                                            }
                                            else{
                                                button.prop("checked", !button.prop("checked"));
                                                showMessage(data['message'],'danger');
                                            }
                                        },
                                        error: function (request, status, error) {
                                            button.prop("checked", !button.prop("checked"));
                                            showMessage(request.responseText,'warning');
                                        }
                                    });
                                }
                            }
                        });
                        
                    });
                    $("#refresh_table").click(function(){
                        table.draw(false);
                    });
                });
                
            </script>
            <?php
        }
    ?>
    
</section>
<!-- /.content -->

<!-- /.content-wrapper -->
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox.css?v=2.1.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox.pack.js?v=2.1.7"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>fancybox/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#myModal').on('show.bs.modal', function(e) {
            var button = e.relatedTarget;
            $(".overlay").css("display","block");
            $.get('<?php echo CURRENT_URL; ?>?action=view_order&invoice=' + $(button).attr('id'), function(data) {
				$('#myModal').find('.modal-content').html(data);
                $(".overlay").css("display","none");
			});
        });
    })
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <form action="<?php echo CURRENT_URL; ?>" class="form-validate" method="post" id="order_update_form">
    <!-- Modal content-->
    <div class="modal-content">
        <!--
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Name</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Invoice:</b> 123456</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Payment Type:</b> Instant</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Total Products:</b> 10</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Total Quantity:</b> 56</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Payment Date:</b> 10-04-2018</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><b>Payment Status:</b> <span class="text-olive">Completed</span> <span class="text-yellow">Completed</span></label>
                    </div>
                </div>
            </div>
            
            <div class="box box-default box-solid">
                <div class="box-header">
                    <h3 class="box-title">Product List</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">#NO</th>
                                <th>CATEGORY</th>
                                <th>PRODUCT</th>
                                <th class="text-right">PRICE</th>
                                <th class="text-right">QTY</th>
                                <th class="text-right">PAYABLE AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>category</td>
                                <td>product</td>
                                <td class="text-right"><i class="fa fa-inr"></i> 100</td>
                                <td class="text-right">3</td>
                                <td class="text-right"><i class="fa fa-inr"></i> 300</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>SUBTOTAL:</b> <i class="fa fa-inr"></i> 300</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
        </div>
        -->
    </div>
    </form>
  </div>
</div>
<button type="button" id="refresh_table" style="display: none;" ></button>