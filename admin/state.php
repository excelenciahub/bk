<?php
    require_once("../include/config.php");
    
	$response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
	$instance = new state_master();

	$slug = isset($_GET['slug'])&&$_GET['slug']!=''?$instance->db_input($_GET['slug']):'';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$instance->db_input($_GET['action']):'view';
    
    $name = '';
    $code = '';
    
    if(ACTION=='add' || ACTION=='edit'){
        if(ACTION=='edit' && $slug!==''){
            $response = json_decode($instance->edit($slug));
            if($response->total>0){
                $name = $instance->db_output($response->records->name);
                $code = $instance->db_output($response->records->code);
            }
        }
    }
    else if(isset($_POST['action'])&&$_POST['action']=='save'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$instance->db_output($_POST['slug']):'';
        $name = isset($_POST['name'])&&$_POST['name']!=''?$instance->db_output($_POST['name']):'';
        $code = isset($_POST['code'])&&$_POST['code']!=''?$instance->db_output($_POST['code']):'';
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
                0=>array('column'=>'id','prefix'=>'sm'),
                1=>array('column'=>'name','prefix'=>'sm'),
                2=>array('column'=>'code','prefix'=>'sm'),
                3=>array('column'=>'status','prefix'=>'sm')
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
            $array[] = $instance->db_output($val->code);
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
        $filters = array();
        $filters[3] = array('label'=>'Status','data'=>array('1'=>'Enabled','0'=>'Disabled')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-building text-purple';
    $header_title = 'State Master';
    require_once(ADMIN_TEMPLATE_FILE);
?>