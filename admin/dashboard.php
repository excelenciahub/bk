<?php
    require_once("../include/config.php");
    $response = json_decode(is_login('admin'));
//printr($_GET);    
    $instance = new order_master();
    $user_instance = new user_master();
    
    $payment_date = isset($_REQUEST['payment_date'])?$instance->db_input($_REQUEST['payment_date']):date("d-m-Y");
    
    if(isset($_POST['action'])&&$_POST['action']=='delete'){
        $invoice = isset($_POST['invoice'])&&$_POST['invoice']!=''?$_POST['invoice']:'';
        $response = $instance->delete($invoice);
        echo $response;exit;
    }
    else if(isset($_POST['action'])&&$_POST['action']=='payment_status'){
        $invoice = isset($_POST['invoice'])&&$_POST['invoice']!=''?$_POST['invoice']:'';
        $status = isset($_POST['status'])&&$_POST['status']!=''?$_POST['status']:0;
        $response = $instance->payment_status($invoice,$status);
        echo $response;exit;
    }
    else if(isset($_GET['action'])&&$_GET['action']=='view_order'){
        $invoice = isset($_GET['invoice'])&&$_GET['invoice']!=''?$instance->db_input($_GET['invoice']):'';
        $response = json_decode($instance->edit($invoice));
        if($response->total>0){
            $user_id = $instance->db_output($response->records->user_id);
            $user_name = $instance->db_output($response->records->user_first_name).' '.$instance->db_output($response->records->user_last_name);
            $mobile_no = $instance->db_output($response->records->mobile_no);
            $payment_type = $instance->db_output($response->records->payment_type);
            $total_products = $instance->db_output($response->records->total_products);
            $total_qty = $instance->db_output($response->records->total_qty);
            $invoice = $instance->db_output($response->records->invoice);
            $payment_status = $instance->db_output($response->records->payment_status);
            $products = json_decode(json_encode($response->records->products),true);
            $total_amount = $instance->db_output($response->records->total_amount);
            $payment_date = display_date($instance->db_output($response->records->payment_date));
            $note = $instance->db_output($response->records->note);
            //printr($products);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $user_name.' ('.$mobile_no.')'; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Invoice:</b> <?php echo $invoice; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Payment Type:</b> <?php echo $payment_type==1?'Instant':'Future'; ?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Total Products:</b> <?php echo $total_products; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Total Quantity:</b> <?php echo $total_qty; ?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Payment Date:</b> <?php echo $payment_date; ?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Payment Status:</b> <?php echo $payment_status==1?'<span class="text-olive">Completed</span>':'<span class="text-yellow">Pending</span>'; ?></label>
                        </div>
                    </div>
                </div>
                
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
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
                                <?php
                                    foreach($products as $key=>$val){
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key+1; ?></td>
                                            <td><?php echo $val['category_name']; ?></td>
                                            <td><?php echo $val['product_name']; ?></td>
                                            <td class="text-right"><i class="fa fa-inr"></i> <?php echo $val['price']; ?></td>
                                            <td class="text-right"><?php echo $val['qty']; ?></td>
                                            <td class="text-right"><i class="fa fa-inr"></i> <?php echo $val['payable_amount']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                <tr>
                                    <td colspan="6" class="text-right"><b>SUBTOTAL:</b> <i class="fa fa-inr"></i> <?php echo $total_amount; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php
        }
        ?>
        
        <?php
        exit;
    }
	else if(isset($_REQUEST['action'])&&$_REQUEST['action']=='view'){
        $column = array(
                0=>array('column'=>'id','prefix'=>'om'),
                1=>array('column'=>'first_name','prefix'=>'um','filter_column'=>'id','child_column'=>'user_id','child_prefix'=>'om'),
                2=>array('column'=>'invoice','prefix'=>'om'),
                3=>array('column'=>'total_products','prefix'=>'om'),
                4=>array('column'=>'total_qty','prefix'=>'om'),
                5=>array('column'=>'total_amount','prefix'=>'om'),
                6=>array('column'=>'payment_date','prefix'=>'om'),
                7=>array('column'=>'payment_status','prefix'=>'om')
            );
        
        $status = '';
        $payment_date = isset($_REQUEST['payment_date'])?format_date($_REQUEST['payment_date']):date("Y-m-d");
        $where = " `om`.`payment_status`='0' AND `om`.`payment_date`='".$payment_date."' ";
        $orderby = array();
        $start = isset($_REQUEST['start'])?$instance->db_input($_REQUEST['start']):0;
        $length = isset($_REQUEST['length'])?$instance->db_input($_REQUEST['length']):RECORD_PER_PAGE;
        $where .= $instance->filter($_REQUEST,$column)!=''?' AND '.$instance->filter($_REQUEST,$column):$instance->filter($_REQUEST,$column);
        //echo $where;exit;
        $orderby = $instance->order($_REQUEST,$column, true);
        $total_records = $instance->count($status);
        $records = json_decode($instance->select($status,$where,$orderby,$start,$length));
        $filtered_records = $instance->count($status,$where);
		$record = array();
        foreach($records->records as $key=>$val){
            $array = array();
            $array[] = isset($_REQUEST['order'][0]['column'])&&$_REQUEST['order'][0]['column']==0&&$_REQUEST['order'][0]['dir']=='desc'?strval($filtered_records-$start-$key):strval($start+$key+1);
            $array[] = $instance->db_output($val->user_first_name).' '.$instance->db_output($val->user_last_name).' ('.$instance->db_output($val->mobile_no).')';
            $array[] = $instance->db_output($val->invoice);
            $array[] = $instance->db_output($val->total_products);
            $array[] = $instance->db_output($val->total_qty);
            $array[] = '<i class="fa fa-inr"></i> '.$instance->db_output($val->total_amount);
            $array[] = display_date($instance->db_output($val->payment_date));
            if($val->payment_status==1){
                $array[] = '<button type="button" invoice="'.$instance->db_output($val->invoice).'" value="0" class="btn btn-xs btn-default text-olive btn-payment-status"><i class="fa fa-check"></i> Completed</button>';
            }
            else{
                $array[] = '<button type="button" invoice="'.$instance->db_output($val->invoice).'" value="1" class="btn btn-xs btn-default text-yellow btn-payment-status"><i class="fa fa-warning"></i> Pending</button>';
            }
            /*
            $array[] = '<a href="'.CURRENT_URL.'?action=edit&invoice='.$instance->db_output($val->invoice).'" id="'.$instance->db_output($val->invoice).'" class="btn btn-xs btn-default text-blue"><i class="fa fa-edit"></i> Edit</a>
					<button type="button" invoice="'.$instance->db_output($val->invoice).'" class="btn btn-xs btn-default text-red btn-delete"><i class="fa fa-trash"></i> Delete</button>';
                    */
            $array[] = '<button type="button" class="btn btn-default btn-xs text-blue" id="'.$instance->db_output($val->invoice).'" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i> View</button>
					<button type="button" invoice="'.$instance->db_output($val->invoice).'" class="btn btn-xs btn-default text-red btn-delete"><i class="fa fa-trash"></i> Delete</button>';
            array_push($record,$array);
        }
        $data = array(
    			"draw"            => isset ( $_REQUEST['draw'] ) ? $instance->db_input($_REQUEST['draw'],true) : 0,
    			"recordsTotal"    => intval( $total_records ),
    			"recordsFiltered" => intval( $filtered_records ),
    			"data"            => $record,
    		);
        echo json_encode($data);exit;
    }
	else{
        $users = json_decode($instance->getUsers())->records;
        $filters = array();
        $filters[1] = array('label'=>'User','data'=>$users);   // 2 is datatable column name (column start from 0)
        //$filters[7] = array('label'=>'Payment Status','data'=>array('1'=>'Completed','0'=>'Pending')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-dashboard text-olive';
    $header_title = 'Dashboard';
    require_once(ADMIN_TEMPLATE_FILE);
?>