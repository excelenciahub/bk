<?php
    require_once("../include/config.php");
    
	$response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
	$instance = new city_master();

	$slug = isset($_GET['slug'])&&$_GET['slug']!=''?$instance->db_input($_GET['slug']):'';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$instance->db_input($_GET['action']):'view';
    
    $name = '';
    $state_id = '';
    
    if(ACTION=='add' || ACTION=='edit'){
        $state_instance = new state_master();
        $state = json_decode($state_instance->select())->records;
        if(ACTION=='edit' && $slug!==''){
            $response = json_decode($instance->edit($slug));
            if($response->total>0){
                $name = $instance->db_output($response->records->name);
                $state_id = $instance->db_output($response->records->state_id);
            }
        }
    }
    else if(isset($_POST['action'])&&$_POST['action']=='save'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$instance->db_output($_POST['slug']):'';
        $name = isset($_POST['name'])&&$_POST['name']!=''?$instance->db_output($_POST['name']):'';
        $state_id = isset($_POST['state_id'])&&$_POST['state_id']!=''?$instance->db_output($_POST['state_id']):'';
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
	else if(isset($_REQUEST['action'])&&$_REQUEST['action']=='view'){
        $column = array(
                0=>array('column'=>'id','prefix'=>'cm'),
                1=>array('column'=>'name','prefix'=>'cm'),
                2=>array('column'=>'name','prefix'=>'sm','filter_column'=>'id','child_column'=>'state_id','child_prefix'=>'cm'),
                3=>array('column'=>'status','prefix'=>'cm')
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
            $array[] = $instance->db_output($val->name);
            $array[] = $instance->db_output($val->state_name);
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
	    $state = json_decode($instance->getState())->records;
        $filters = array();
        $filters[2] = array('label'=>'State','data'=>$state);
        $filters[3] = array('label'=>'Status','data'=>array('1'=>'Enabled','0'=>'Disabled')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-building text-purple';
    $header_title = 'City Master';
    require_once(ADMIN_TEMPLATE_FILE);
?>