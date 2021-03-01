<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

    <!-- Main content -->
    <section class="content">
        <?php require_once(DIR_INCLUDES.'alerts.php'); ?>
        <!-- Default box -->
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Pending Payment For Today</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <span class="dropdown">
                    	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right">
                    		<li><a href="javascript:void(0);" id="clear_state"><i class="fa fa-refresh"></i> Reset Filter</a></li>
                    	</ul>
                    </span>
                </div>
            </div>
            <div class="box-body">
                <form action="<?php echo CURRENT_URL; ?>" id="formFilter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Payment Date </label>
                                <input type="text" class="form-control datepicker" name="payment_date" id="payment_date" value="<?php echo $payment_date; ?>" readonly="true" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <div class="form-group">
                                <button type="submit" id="filter" class="btn btn-sm btn-default text-olive"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
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
        
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">No of orders</h3>
            </div>
            <div class="box-body">
                <div class="chart_container" id="no_of_orders_chart_container"></div>
            </div>
        </div>
        
    </section>
    <!-- /.content -->
    

<style type="text/css">
    .chart_container {
      min-width: 310px;
      height: 400px;
      margin: 0 auto
    }
</style>

<!-- DataTables -->
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>datatables/css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datatables/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo ADMIN_PLUGINS; ?>highcharts/highcharts.js"></script>
<script src="<?php echo ADMIN_PLUGINS; ?>highcharts/series-label.js"></script>
<script src="<?php echo ADMIN_PLUGINS; ?>highcharts/exporting.js"></script>

<script type="text/javascript">
    function no_of_orders(filter=""){
        $.ajax({
          url: "<?php echo ADMIN_URL; ?>charts/no_of_orders.php",
          data: filter,
          cache: false,
          success: function(html){
            $("#no_of_orders_chart_container").html(html);
          },
          error: function (request, status, error) {
            $("#no_of_orders_chart_container").html(request.responseText);
          }
        });
    }
    $(document).ready(function() {
        no_of_orders();
        
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
                    "action": "view",
                    "payment_date": $("#payment_date").val()
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
    });
    
</script>
<link type="text/css" href="<?php echo ADMIN_PLUGINS; ?>datetimepicker/datetimepicker.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ADMIN_PLUGINS; ?>datetimepicker/datetimepicker.js"></script>
                
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
        $('.datepicker').datetimepicker({
            format:'d-m-Y',
            timepicker: false,
            scrollInput:false,
        });
    })
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        
    </div>

  </div>
</div>
