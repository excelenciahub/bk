<?php
    require_once("../include/config.php");
    
	$response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
	$instance = new user_master();

	$slug = isset($_GET['slug'])&&$_GET['slug']!=''?$instance->db_input($_GET['slug']):'';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$instance->db_input($_GET['action']):'view';
    
    $first_name = '';
    $last_name = '';
    $image = '';
    $mobile_no = '';
    $city_id = '';
    $state_id = '';
    $gst_no = '';
    $city = array();
    $state = array();
    
    if(ACTION=='add' || ACTION=='edit'){
        $state_instance = new state_master();
        $state = json_decode($state_instance->select())->records;
        if(ACTION=='edit' && $slug!==''){
            $response = json_decode($instance->edit($slug));
            if($response->total>0){
                $first_name = $instance->db_output($response->records->first_name);
                $last_name = $instance->db_output($response->records->last_name);
                $image = $instance->db_output($response->records->image);
                $mobile_no = $instance->db_output($response->records->mobile_no);
                $state_id = $instance->db_output($response->records->state_id);
                $city_id = $instance->db_output($response->records->city_id);
                $gst_no = $instance->db_output($response->records->gst_no);
            }
        }
    }
    else if(isset($_POST['action'])&&$_POST['action']=='save'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$instance->db_output($_POST['slug']):'';
        $state_id = isset($_POST['state_id'])&&$_POST['state_id']!=''?$instance->db_output($_POST['state_id']):'';
        $city_id = isset($_POST['city_id'])&&$_POST['city_id']!=''?$instance->db_output($_POST['city_id']):'';
        $gst_no = isset($_POST['gst_no'])&&$_POST['gst_no']!=''?$instance->db_output($_POST['gst_no']):'';
        $first_name = isset($_POST['first_name'])&&$_POST['first_name']!=''?$instance->db_output($_POST['first_name']):'';
        $last_name = isset($_POST['last_name'])&&$_POST['last_name']!=''?$instance->db_output($_POST['last_name']):'';
        $mobile_no = isset($_POST['mobile_no'])&&$_POST['mobile_no']!=''?$instance->db_output($_POST['mobile_no']):'';
        $response = $instance->save($_POST);
        echo $response;exit;
    }
	else if(isset($_POST['action'])&&$_POST['action']=='delete'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$_POST['slug']:'';
        $response = $instance->delete($slug);
        echo $response;exit;
    }
    else if(isset($_POST['action'])&&$_POST['action']=='status'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$_POST['slug']:'';
        $status = isset($_POST['status'])&&$_POST['status']!=''?$_POST['status']:0;
        $response = $instance->status($slug,$status);
        echo $response;exit;
    }
    else if(isset($_POST['action'])&&$_POST['action']=='get_city'){
        $state_id = isset($_POST['state_id'])&&$_POST['state_id']!=''?$instance->db_output($_POST['state_id'],true):0;
        $city_id = isset($_POST['city_id'])&&$_POST['city_id']!=''?$instance->db_output($_POST['city_id'],true):0;
        $where = " `cm`.`state_id`='".$state_id."' ";
        $city_instance = new city_master();
        $city = json_decode($city_instance->select("",$where))->records;
        ?>
        <option value="">Select City</option>
        <?php
        foreach($city as $key=>$val){
            ?>
            <option <?php echo $val->id==$city_id?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
            <?php
        }
        exit;
    }
	else if(isset($_REQUEST['action'])&&$_REQUEST['action']=='view'){
        $column = array(
                0=>array('column'=>'id','prefix'=>'um'),
                1=>array('column'=>'first_name','prefix'=>'um'),
                2=>array('column'=>'gst_no','prefix'=>'um'),
                3=>array('column'=>'name','prefix'=>'sm','filter_column'=>'id','child_column'=>'state_id','child_prefix'=>'um'),
                4=>array('column'=>'name','prefix'=>'cm','filter_column'=>'id','child_column'=>'city_id','child_prefix'=>'um'),
                5=>array('column'=>'status','prefix'=>'um')
            );
        
        $status = '';
        $where = '';
        $orderby = array();
        $start = isset($_REQUEST['start'])?$instance->db_input($_REQUEST['start']):0;
        $length = isset($_REQUEST['length'])?$instance->db_input($_REQUEST['length']):RECORD_PER_PAGE;
        $where = $instance->filter($_REQUEST,$column);
        $orderby = $instance->order($_REQUEST,$column, true);
        $total_records = $instance->count($status);
        $records = json_decode($instance->select($status,$where,$orderby,$start,$length));
        $filtered_records = $instance->count($status,$where);
		$record = array();
        foreach($records->records as $key=>$val){
            $array = array();
            $array[] = isset($_REQUEST['order'][0]['column'])&&$_REQUEST['order'][0]['column']==0&&$_REQUEST['order'][0]['dir']=='desc'?strval($filtered_records-$start-$key):strval($start+$key+1);
            $array[] = $instance->db_output($val->first_name).' '.$instance->db_output($val->last_name);
            $array[] = $instance->db_output($val->gst_no);
            $array[] = $instance->db_output($val->state_name);
            $array[] = $instance->db_output($val->city_name);
            $array[] = $instance->db_output($val->mobile_no);
            if($val->status==1){
                $array[] = '<button type="button" slug="'.$instance->db_output($val->slug).'" value="0" class="btn btn-xs btn-default text-olive btn-status"><i class="fa fa-check"></i> Enabled</button>';
            }
            else{
                $array[] = '<button type="button" slug="'.$instance->db_output($val->slug).'" value="1" class="btn btn-xs btn-default text-yellow btn-status"><i class="fa fa-warning"></i> Disabled</button>';
            }
            $array[] = '<a href="'.CURRENT_URL.'?action=edit&slug='.$instance->db_output($val->slug).'" id="'.$instance->db_output($val->slug).'" class="btn btn-xs btn-default text-blue"><i class="fa fa-edit"></i> Edit</a>
					<button type="button" slug="'.$instance->db_output($val->slug).'" class="btn btn-xs btn-default text-red btn-delete"><i class="fa fa-trash"></i> Delete</button>';
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
    	$city = json_decode($instance->getCity())->records;
        $state = json_decode($instance->getState())->records;
        $filters = array();
        $filters[4] = array('label'=>'State','data'=>$state);
        $filters[4] = array('label'=>'City','data'=>$city);   // 2 is datatable column name (column start from 0)
        $filters[5] = array('label'=>'Status','data'=>array('1'=>'Enabled','0'=>'Disabled')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-users text-olive';
    $header_title = 'User Master';
    require_once(ADMIN_TEMPLATE_FILE);
?>