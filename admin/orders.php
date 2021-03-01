<?php
    require_once("../include/config.php");
    
	$response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
	$instance = new order_master();
    $user_instance = new user_master();
    $cat_instance = new category_master();
    
	$invoice = isset($_GET['invoice'])&&$_GET['invoice']!=''?$instance->db_input($_GET['invoice']):'';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$instance->db_input($_GET['action']):'view';
    
    $user_id = '';
    $payment_type = '';
    $payment_date = '';
    $price = '';
    $total_amount = '';
    $note = '';
    
    $categories = array();
    $users = array();
    $products = array();
    
    if(ACTION=='add' || ACTION=='edit'){
        if(ACTION=='edit'){
            header("location:".$_SERVER['HTTP_REFERER']);exit;
        }
        $categories = json_decode($cat_instance->select(1))->records;
        $users = json_decode($user_instance->select(1))->records;
        if(ACTION=='edit' && $invoice!==''){
            $response = json_decode($instance->edit($invoice));
            if($response->total>0){
                $user_id = $instance->db_output($response->records->user_id);
                $payment_type = $instance->db_output($response->records->payment_type);
                $products = json_decode(json_encode($response->records->products),true);
                $total_amount = $instance->db_output($response->records->total_amount);
                $payment_date = display_date($instance->db_output($response->records->payment_date));
                $note = $instance->db_output($response->records->note);
                //printr($products);
            }
        }
    }
    else if(isset($_POST['action'])&&$_POST['action']=='save'){
        $id = isset($_POST['id'])&&$_POST['id']!=''?$instance->db_output($_POST['id']):'';
        $user_id = isset($_POST['user_id'])&&$_POST['user_id']!=''?$instance->db_output($_POST['user_id']):'';
        $products = isset($_POST['products'])&&$_POST['products']!=''?$_POST['products']:array();
        $payment_type = isset($_POST['payment_type'])&&$_POST['payment_type']!=''?$instance->db_output($_POST['payment_type']):'';
        $payment_date = isset($_POST['payment_date'])&&$_POST['payment_date']!=''?display_date($instance->db_output($_POST['payment_date'])):'';
        $note = isset($_POST['note'])&&$_POST['note']!=''?$instance->db_output($_POST['note']):'';
        $response = $instance->save($_POST);
        echo $response;exit;
    }
    else if(isset($_POST['action'])&&$_POST['action']=='update'){
        $response = $instance->update($_POST);
        echo $response;exit;
    }
	else if(isset($_POST['action'])&&$_POST['action']=='delete'){
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
    else if(isset($_GET['action'])&&($_GET['action']=='pdf'||$_GET['action']=='print')){
        //ini_set("display_errors",0);
        
        $invoice = isset($_GET['invoice'])&&$_GET['invoice']!=''?$instance->db_input($_GET['invoice']):'';
        
        $response = json_decode($instance->edit($invoice));
        if($response->total>0){
            $user_id = $instance->db_output($response->records->user_id);
            $user_name = $instance->db_output($response->records->user_first_name).' '.$instance->db_output($response->records->user_last_name);
            $mobile_no = $instance->db_output($response->records->mobile_no);
			$city_name = $instance->db_output($response->records->city_name);
            $payment_type = $instance->db_output($response->records->payment_type);
            $payment_type_name = $payment_type==1?'Instant':'Future';
            $total_products = $instance->db_output($response->records->total_products);
            $total_qty = $instance->db_output($response->records->total_qty);
            $invoice = $instance->db_output($response->records->invoice);
            $payment_status = $instance->db_output($response->records->payment_status);
            $products = json_decode(json_encode($response->records->products),true);
            $total_amount = $instance->db_output($response->records->total_amount);
            $payment_date = display_date($instance->db_output($response->records->payment_date));
            $note = $instance->db_output($response->records->note);
            $state_name = $instance->db_output($response->records->state_name);
            $state_code = $instance->db_output($response->records->state_code);
            $user_gst_no = $instance->db_output($response->records->user_gst_no);
            //printr($response);
            
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetPrintHeader(true);
            $pdf->SetPrintFooter(true);
            $pdf->SetMargins(10,51, 10, true);
            $pdf->AddPage();
            
            $pdf->SetFont('times','',10);
            
            $html = '';
            
            $html .= '<table cellspacing="0" cellpadding="5" border="0" width="100%" style="border-top: none;">
                <tr nobr="true" style="background-color: #f1f1f1;">
                    <td colspan="3" align="center" style="border: 1px solid #ddd;font-weight:bold;text-align:center;">BILL OF SUPPLY</td>
                </tr>
                <tr nobr="true">
                    <td width="80%" colspan="2" align="center" style="border: 1px solid #ddd;border-right:none;font-weight:bold;text-align:center;">BUYER DETAIL</td>
                    <td width="20%" align="center" style="border: 1px solid #ddd;font-weight:bold;text-align:center;">Invoice No.</td>
                </tr>
                <tr nobr="true">
                    <td colspan="2" style="border: 1px solid #ddd;border-right:none;border-bottom:none;border-top:none;text-align:left;"><b>Name: </b> '.$user_name.'</td>
                    <td style="border: 1px solid #ddd;border-top: none;border-bottom:none;text-align:center;">'.$invoice.'</td>
                </tr>
                <tr nobr="true">
                    <td colspan="2" style="border: 1px solid #ddd;border-right:none;border-bottom:none;border-top:none;text-align:left;"><b>Mobile: </b> '.$mobile_no.'</td>
                    <td style="border: 1px solid #ddd;border-top: none;font-weight:bold;text-align:center;"></td>
                </tr>
                <tr nobr="true">
                    <td colspan="2" style="border: 1px solid #ddd;border-top: none;border-right:none;text-align:left;"><b>Place: </b> '.$city_name.'</td>
                    <td align="center" valign="top" style="border: 1px solid #ddd;border-top: none;;border-bottom:none;font-weight:bold;text-align:center;">Invoice Date</td>
                </tr>
                <tr nobr="true">
                    <td width="48%" style="border: 1px solid #ddd;border-top: none;border-right:none;text-align:left;"><b>State: </b> '.$state_name.'</td>
                    <td width="32%" style="border: 1px solid #ddd;border-top: none;border-right:none;text-align:left;"><b>State Code: </b> '.$state_code.'</td nobr="true">
                    <td style="border: 1px solid #ddd;border-top: none;border-right:none;border-bottom:none;text-align:center;">'.date('d-m-Y h:i A').'</td>
                </tr>
                <tr nobr="true">
                    <td colspan="2" style="border: 1px solid #ddd;border-top: none;border-right:none;text-align:left;"><b>GSTIN: </b> '.$user_gst_no.'</td>
                    <td style="border: 1px solid #ddd;border-top: none;border-right:none;text-align:left;"></td>
                </tr>
            </table>';
            
            $html .= '<table border="0" cellspacing="0" cellpadding="5" border="0" width="100%">';
            
            $html .= '<tr nobr="true" style="background-color: #f1f1f1;">
                        <th colspan="6" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">ORDER DETAIL</th>
                    </tr>';
            $html .= '<tr nobr="true">
                        <th colspan="2" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">TOTAL PRODUCTS</th>
                        <th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">'.$total_products.'</th>
                    </tr>';
            $html .= '<tr nobr="true">
                        <th colspan="2" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">TOTAL QUANTITY</th>
                        <th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">'.$total_qty.'</th>
                    </tr>';
            $html .= '<tr nobr="true">
                        <th colspan="2" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">PAYMENT TYPE</th>
                        <th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">'.$payment_type_name.'</th>
                    </tr>';
            $html .= '<tr nobr="true">
                        <th colspan="2" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">PAYMENT STATUS</th>';
                        if($payment_status==1){
                            $html .= '<th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;color:#3d9970;">Completed</th>';
                        }
                        else{
                            $html .= '<th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;color:#e08e0b;">Pending</th>';
                        }
                $html .= '</tr>';
            $html .= '<tr nobr="true">
                        <th colspan="2" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">PAYMENT DATE</th>
                        <th colspan="4" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">'.$payment_date.'</th>
                    </tr>';
                            
            $html .= '<tr nobr="true" style="background-color: #f1f1f1;" align="center">
                            <th width="10%" style="border: 1px solid #ddd;font-weight:bold;text-align:center;">#Sr. No.</th>
                            <th width="20%" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">CATEGORY</th>
                            <th width="20%" style="border: 1px solid #ddd;font-weight:bold;text-align:left;">PRODUCT</th>
                            <th width="15%" style="border: 1px solid #ddd;font-weight:bold;text-align:right;">QTY/UNIT</th>
                            <th width="15%" style="border: 1px solid #ddd;font-weight:bold;text-align:right;">RATE</th>
                            <th width="20%" style="border: 1px solid #ddd;font-weight:bold;text-align:right;">AMOUNT</th>
                        </tr>';
            
            foreach($products as $key=>$val){
                $html .= '<tr nobr="true">
                            <td style="border: 1px solid #ddd;text-align:center;">'.($key+1).'</td>
                            <td style="border: 1px solid #ddd;text-align:left;">'.$val['category_name'].'</td>
                            <td style="border: 1px solid #ddd;text-align:left;">'.$val['product_name'].'</td>
                            <td style="border: 1px solid #ddd;text-align:right;">'.$val['qty'].'</td>
                            <td style="border: 1px solid #ddd;text-align:right;"><i class="fa fa-inr"></i> <span class="price">'.$val['price'].'</span></td>
                            <td style="border: 1px solid #ddd;text-align:right;"><i class="fa fa-inr"></i> <span class="payable_amount">'.$val['payable_amount'].'</span></td>
                        </tr>';
            }
            
            $html .= '<tr nobr="true">
                            <td colspan="6" style="border: 1px solid #ddd;text-align:right;"><b>TOTAL:</b> <i class="fa fa-inr"></i> <span class="total_amount">'.$total_amount.'</span></td>
                        </tr>';
            $html .= '<tr>
                <td colspan="6" style="border: 1px solid #ddd;text-align:left;"><b>TOTAL AMOUNT IN WORDS: </b> <i class="fa fa-inr"></i> <span class="total_amount">';
                
                $tempNum = explode( '.' , $total_amount );
                $convertedNumber = ( isset( $tempNum[0] ) ? convertNumber( $tempNum[0] ) : '' );
                //  Use the below line if you don't want 'and' in the number before decimal point
                $convertedNumber = str_replace( ' and ' ,' ' ,$convertedNumber );
                //  In the below line if you want you can replace ' and ' with ' , '
                $convertedNumber .= ( ( isset( $tempNum[0] ) and isset( $tempNum[1] ) && $tempNum[1]>0 )  ? ' and ' : '' );
                $convertedNumber .= ( isset( $tempNum[1] ) && $tempNum[1]>0 ? convertNumber( $tempNum[1] ) .' paise' : '' );
                $html .= ucwords($convertedNumber).' Only.';
                //convertNumber($total_amount);
                $html .= '</span></td>
            </tr>';
            $html .= '<tr>
                <td colspan="6" style="border: 1px solid #ddd;text-align:left;"><b>Note: </b> '.$note.'</td>
            </tr>';
                        
            $html .= '</table>';
            
            if($_GET['action']=='print___'){
				echo $html;
				?>
                <style type="text/css">
                    @media print {
                      @page { margin: 0; }
                      body { margin: 1cm; }
                    }
                </style>
				<script type="text/javascript">
					window.print();
				</script>
				<?php
				exit;
				$printer = 'HP Printer 1020';
				$obj = printer_open($printer);
				printer_write($obj,$html);
				exit;
			}
            $pdf->writeHTML($html, false, 0, false, 0);
            if($_GET['action']=='print'){
                $pdf->IncludeJS("print();");
            }
            $pdf->Output($invoice.'.pdf','I');
        }
        exit;    
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
                <div id="update_order" class="modal-body">
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
                                <label><b>Total Quantity:</b> <span class="total_qty"><?php echo $total_qty; ?></span></label>
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
                        <div class="box-body form-inline">
                            <table class="table table-bordered table-hover table-stripped">
                                <thead>
                                    <tr>
                                        <th class="text-center">#NO</th>
                                        <th>CATEGORY</th>
                                        <th>PRODUCT</th>
                                        <th class="text-right">PRICE</th>
                                        <th class="">QTY</th>
                                        <th class="text-right">PAYABLE AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <script type="text/javascript">
                                        var product_detail = [];
                                    </script>
                                    <?php
                                        foreach($products as $key=>$val){
                                            $prices = json_decode($val['prices']);
                                            $price_table = '<table class="table table-bordered table-hover table-striped">
                                                                        <tr>
                                                                            <th class="text-center" colspan="2">QTY</th>
                                                                            <th class="text-center" colspan="2">PRICE</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Min</th>
                                                                            <th>Max</th>
                                                                            <th>Instant</th>
                                                                            <th>Future</th>
                                                                        </tr>';
                                                                    foreach($prices as $k=>$v){
                                                                        $price_table .= '<tr>
                                                                                <td>'.$v->min_qty.'</td>
                                                                                <td>'.$v->max_qty.'</td>
                                                                                <td><i class="fa fa-inr"></i> '.$v->instant_price.'</td>
                                                                                <td><i class="fa fa-inr"></i> '.$v->future_price.'</td>
                                                                            </tr>';
                                                                    }
                                            $price_table .='</table>';
                                            ?>
                                            <script type="text/javascript">
                                                product_detail[<?php echo $val['product_id']; ?>] = [];
                                                product_detail[<?php echo $val['product_id']; ?>]['price'] = <?php echo $val['prices']; ?>;
                                            </script>
                                            <tr>
                                                <td class="text-center"><?php echo $key+1; ?></td>
                                                <td><?php echo $val['category_name']; ?></td>
                                                <td><?php echo $val['product_name']; ?></td>
                                                <td class="text-right"><i class="fa fa-inr"></i> <span class="price"><?php echo $val['price']; ?></span> <i class="fa fa-question-circle text-aqua" data-toggle="popover" data-html="true" title="Price List" data-content='<?php echo $price_table; ?>'></i></td>
                                                <td class="">
                                                    <input type="hidden" name="product_id" class="product_id" value="<?php echo $val['product_id']; ?>" />
                                                    <input type="number" min="0" max="<?php echo $val['qty']; ?>" class="qty form-control input-sm" name="qty[<?php echo $val['order_item_id']; ?>]" value="<?php echo $val['qty']; ?>" /></td>
                                                <td class="text-right"><i class="fa fa-inr"></i> <span class="payable_amount"><?php echo $val['payable_amount']; ?></span></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-right"><b>SUBTOTAL:</b> <i class="fa fa-inr"></i> <span class="total_amount"><?php echo $total_amount; ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="invoice" value="<?php echo $invoice; ?>" />
                    <input type="hidden" name="payment_type" id="payment_type" value="<?php echo $payment_type; ?>" />
                    <button type="submit" name="save" id="save" class="btn btn-sm btn-default text-olive"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                </div>
                
            <script type="text/javascript">
                $('[data-toggle="popover"]').popover();
                //var product_detail = [];
                console.log(product_detail);
                var qt = 0;
                $(".qty").keydown(function(){
                    qt = $(this).val();
                });
                $(".qty").keyup(function(){
                    var payment_type = parseInt($("#payment_type").val());
                    var product_id = $(this).closest('tr').find(".product_id").val();
                    var price = 0;
                    var qty = $(this).val();
                    $.each(product_detail[product_id]['price'],function (key, val){
                        
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
                        $.each(product_detail[product_id]['price'],function (key, val){
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
                    $(this).closest('tr').find('.price').html(price);
                    var price = parseFloat($(this).closest('tr').find('.price').html());
                    //var qty = $(this).val();
                    var old_payable_amount = parseFloat($(this).closest('tr').find('.payable_amount').html());
                    var new_payable_amount = price*qty;
                    $(this).closest('tr').find('.payable_amount').html(parseFloat(new_payable_amount).toFixed(2));
                    var diff = parseFloat(new_payable_amount-old_payable_amount);
                    var total_amount = parseFloat($(this).closest('tbody').find('.total_amount').html())+parseFloat(diff);
                    
                    $(this).closest('tbody').find('.total_amount').html(parseFloat(total_amount).toFixed(2));
                    
                    var total_qty = 0;
                    $('.qty').each(function(){
                        total_qty = total_qty + parseInt($(this).val());
                    });
                    
                    //var total_qty = parseInt($(".total_qty").html())+(parseInt(qty)-parseInt(qt));
                    //console.log(total_qty);
                    $(".total_qty").html(total_qty);
                });
            </script>
            <?php
        }
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
        $where = '';
        $orderby = array();
        $start = isset($_REQUEST['start'])?$instance->db_input($_REQUEST['start']):0;
        $length = isset($_REQUEST['length'])?$instance->db_input($_REQUEST['length']):RECORD_PER_PAGE;
        $where = $instance->filter($_REQUEST,$column);
        //echo $where;exit;
        $orderby = $instance->order($_REQUEST,$column, true);
        $total_records = $instance->count($status);
        $records = json_decode($instance->select($status,$where,$orderby,$start,$length));
        $filtered_records = $instance->count($status,$where);
		$record = array();
        foreach($records->records as $key=>$val){
            $array = array();
            $array[] = isset($_REQUEST['order'][0]['column'])&&$_REQUEST['order'][0]['column']==0&&$_REQUEST['order'][0]['dir']=='desc'?strval($filtered_records-$start-$key):strval($start+$key+1);
            $array[] = $instance->db_output($val->user_first_name).' '.$instance->db_output($val->user_last_name).' ('.$instance->db_output($val->city_name).')';
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
            $array[] = '<a href="'.CURRENT_URL.'?action=pdf&invoice='.$instance->db_output($val->invoice).'" target="_blank" class="btn btn-xs btn-default text-red"><i class="fa fa-file-pdf-o"></i> PDF</a> 
			<a href="'.CURRENT_URL.'?action=print&invoice='.$instance->db_output($val->invoice).'" target="_blank" class="btn btn-xs btn-default text-olive"><i class="fa fa-print"></i> Print</a> 
			<button type="button" class="btn btn-default btn-xs text-blue" id="'.$instance->db_output($val->invoice).'" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i> View</button>
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
        $filters[7] = array('label'=>'Payment Status','data'=>array('1'=>'Completed','0'=>'Pending')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-cart-plus text-fuchsia';
    $header_title = 'Order Product';
    require_once(ADMIN_TEMPLATE_FILE);
?>