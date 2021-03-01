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
                        <h3 class="box-title"><?php echo ucfirst($action); ?> City</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <span class="dropdown">
                            	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            	<ul class="dropdown-menu dropdown-menu-right">
                            		<li><a href="<?php echo CURRENT_URL; ?>" class="text-olive"><i class="fa fa-eye"></i> View City</a></li>
                            	</ul>
                            </span>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State <span class="text-red star">*</span></label>
                                    <select class="form-control chosen-select" name="state_id" id="state_id">
                                        <option value="">Select State</option>
                                        <?php
                                            foreach($state as $key=>$val){
                                                ?>
                                                <option <?php echo $state_id==$val->id?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
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
            <?php
        }
        else if($action=='view'){
            ?>
            <!-- Default box -->
            <div class="box box-widget box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">City</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <span class="dropdown">
                        	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                        	<ul class="dropdown-menu dropdown-menu-right">
                        		<li><a href="<?php echo CURRENT_URL; ?>?action=add"><i class="fa fa-plus"></i> Add City</a></li>
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
                                <th>STATE</th>
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