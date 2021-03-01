<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

<!-- Main content -->
<section class="content">
    <?php require_once(DIR_INCLUDES.'alerts.php'); ?>
    <?php
        if($action=='add'||$action=='edit'){
            ?>
            <form action="<?php echo CURRENT_URL; ?>" class="form-validate" method="post" enctype="multipart/form-data">
                <!-- Default box -->
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ucfirst($action); ?> Product</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <span class="dropdown">
                            	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            	<ul class="dropdown-menu dropdown-menu-right">
                            		<li><a href="<?php echo CURRENT_URL; ?>" class="text-olive"><i class="fa fa-eye"></i> View Products</a></li>
                            	</ul>
                            </span>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category <span class="text-red star">*</span></label>
                                    <select class="form-control chosen-select" name="category_id" id="category_id">
                                        <option value="0">Select Category</option>
                                        <?php
                                            foreach($categories as $key=>$val){
                                                ?>
                                                <option <?php echo $category_id==$val->id?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-red star">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="clearfix">
                                    <?php
                                        if($image!=''&&file_exists(DIR_PRODUCTS.get_thumb($image,'135x100'))){
                                            ?>
                                            <div class="image_container">
                                                <a class="fancybox" rel="gallery0" href="<?php echo SITE_PRODUCTS.$image; ?>">
                                                    <img src="<?php echo SITE_PRODUCTS.get_thumb($image,'135x100'); ?>" class="img-thumbnail edit_box_image" />
                                                </a>
                                                <a href="<?php echo CURRENT_URL; ?>?action=delete_image&slug=<?php echo $slug; ?>&image=<?php echo $image; ?>&type=other" onclick="return confirm('Are you sure?');" class="image_delete_link" title="Delete Image"><i class="fa fa-remove"></i></a>
                                            </div>
                                            <?php
                                        }
                                        else if($slug!=''){
                                            ?>
                                            <a class="fancybox" rel="gallery0" href="<?php echo SITE_PRODUCTS.NOIMAGEFOUND; ?>">
                                                <img style="width: 150px; height: 150px;" src="<?php echo SITE_PRODUCTS.NOIMAGEFOUND; ?>" class="img-thumbnail" />
                                            </a>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image" id="image" class="form-control input-sm" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="clearfix">
                                <?php
                                    if(count($other_images)>0){
                                        foreach($other_images as $key=>$val){
                                            if($val!=''&&file_exists(DIR_PRODUCTS.get_thumb($val,'135x100'))){
                                                ?>
                                                <div class="image_container">
                                                    <a class="fancybox" rel="gallery1" href="<?php echo SITE_PRODUCTS.$val; ?>">
                                                        <img src="<?php echo SITE_PRODUCTS.get_thumb($val,'135x100'); ?>" class="img img-thumbnail edit_box_image" />
                                                    </a>
                                                    <a href="<?php echo CURRENT_URL; ?>?action=delete_image&slug=<?php echo $slug; ?>&image=<?php echo $val; ?>" onclick="return confirm('Are you sure?');" class="image_delete_link" title="Delete Image"><i class="fa fa-remove"></i></a>
                                                </div>
                                                
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                                </div>
                                <div class="form-group">
                                    <label>Other Images (Multiple)</label>
                                    <input type="file" name="other_image[]" class="form-control" multiple="true" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Price <span class="text-red star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="price_box">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Min. QTY <span class="text-red star">*</span></label>
                                    <input type="number" class="form-control" name="price[0][min_qty]" value="<?php echo isset($price[0]['min_qty'])?$price[0]['min_qty']:''; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Max. QTY <span class="text-red star">*</span></label>
                                    <input type="number" class="form-control" name="price[0][max_qty]" value="<?php echo isset($price[0]['max_qty'])?$price[0]['max_qty']:''; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Instance Payment Price <span class="text-red star">*</span></label>
                                    <input type="number" class="form-control" name="price[0][instant_price]" value="<?php echo isset($price[0]['instant_price'])?$price[0]['instant_price']:''; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Future Payment Price <span class="text-red star">*</span></label>
                                    <input type="number" class="form-control" name="price[0][future_price]" value="<?php echo isset($price[0]['future_price'])?$price[0]['future_price']:''; ?>" />
                                </div>
                            </div>
                            <div class="col-sm-2 remove_row">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-group">
                                    <button type="button" class="btn btn-default remove_price"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                            foreach($price as $key=>$val){
                                if($key==0){
                                    continue;
                                }
                                ?>
                                <div class="row" id="price_box">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Min. QTY <span class="text-red star">*</span></label>
                                            <input type="number" class="form-control" name="price[<?php echo $key; ?>][min_qty]" value="<?php echo $val['min_qty']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Max. QTY <span class="text-red star">*</span></label>
                                            <input type="number" class="form-control" name="price[<?php echo $key; ?>][max_qty]" value="<?php echo $val['max_qty']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Instant Payment <span class="text-red star">*</span></label>
                                            <input type="number" class="form-control" name="price[<?php echo $key; ?>][instant_price]" value="<?php echo $val['instant_price']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Future Payment <span class="text-red star">*</span></label>
                                            <input type="number" class="form-control" name="price[<?php echo $key; ?>][future_price]" value="<?php echo $val['future_price']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default remove_price"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        
                        <div id="more_price"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-aqua">* Leave Max. QTY blank or '0' for infinite QTY</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-default" id="add_price"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
                    <div class="box-footer">
                        <input type="hidden" name="slug" id="slug" value="<?php echo $slug; ?>" />
                        <input type="hidden" name="action" id="action" value="save" />
                        <button type="submit" class="btn btn-default btn-sm text-olive"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
                <!-- /.box -->
            </form>
            <style type="text/css">
                .remove_row{
                    display: none;
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function(){
                    var price = <?php echo count($price); ?>;
                    $("#add_price").click(function(){
                        var id = $(this).val();
                        price++;
                        $("#price_box").clone(true).find("input,select").each(function () {    
                            $(this).val("");
                            var name = $(this).attr("name");
                            var new_name = name.replace("[0]","["+price+"]");
                            $(this).attr("name",new_name);
                        }).end().find('.remove_row').css('display','block').end().prop('id','price_box_'+price).insertBefore("#more_price");
                    });
                    $(".remove_price").click(function(){
                        $(this).closest(".row").fadeOut(300, function(){
                            $(this).remove();
                        });
                    });
                })
            </script>
            <?php
        }
        else if($action=='view'){
            ?>
            <!-- Default box -->
            <div class="box box-widget box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Products</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <span class="dropdown">
                        	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                        	<ul class="dropdown-menu dropdown-menu-right">
                        		<li><a href="<?php echo CURRENT_URL; ?>?action=add"><i class="fa fa-plus"></i> Add Product</a></li>
                        		<li><a href="javascript:void(0);" id="clear_state"><i class="fa fa-refresh"></i> Reset Filter</a></li>
                        	</ul>
                        </span>
                    </div>
                </div>
                <div class="box-body">
                    <table id="data-table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#NO</th>
                                <th>NAME</th>
                                <th>CATEGORY</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
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
                            $('td', row).eq(3).addClass('text-center');
                            $('td', row).eq(4).addClass('text-center');
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
                                    var slug = $(element).attr('slug');
                                    var data = 'slug='+slug+'&action=delete';
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
                        var slug = $(this).attr('slug');
                        var status = $(this).val();
                        var data = 'slug='+ slug +"&status="+status+"&action=status";
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
                });
                
            </script>
            <?php
        }
    ?>
    
</section>
<!-- /.content -->


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
        $(".fancybox").fancybox({
    		openEffect	: 'none',
    		closeEffect	: 'none'
    	});
    })
</script>