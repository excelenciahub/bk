<?php
    require_once("../include/config.php");
    
	$response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
	$instance = new product_master();
    $cat_instance = new category_master();
    
	$slug = isset($_GET['slug'])&&$_GET['slug']!=''?$instance->db_input($_GET['slug']):'';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$instance->db_input($_GET['action']):'view';
    
    $category_id = '';
    $name = '';
    $image = '';
    $other_images = array();
    $categories = array();
    $price = array();
    
    if(ACTION=='add' || ACTION=='edit'){
        $categories = json_decode($cat_instance->select())->records;
        if(ACTION=='edit' && $slug!==''){
            $response = json_decode($instance->edit($slug));
            if($response->total>0){
                $category_id = $instance->db_output($response->records->category_id);
                $name = $instance->db_output($response->records->name);
                $image = $instance->db_output($response->records->image);
                $other_images = json_decode($response->records->other_images,true);
                $price = json_decode(json_encode($response->records->price),true);
            }
        }
    }
    else if(isset($_POST['action'])&&$_POST['action']=='save'){
        $slug = isset($_POST['slug'])&&$_POST['slug']!=''?$instance->db_output($_POST['slug']):'';
        $category_id = isset($_POST['category_id'])&&$_POST['category_id']!=''?$instance->db_output($_POST['category_id']):'';
        $name = isset($_POST['name'])&&$_POST['name']!=''?$instance->db_output($_POST['name']):'';
        $price = isset($_POST['price'])&&$_POST['price']!=''?$_POST['price']:array();
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
    else if(isset($_GET['action'])&&$_GET['action']=='delete_image'){
        $slug = isset($_GET['slug'])?$_GET['slug']:'';
        $image = isset($_GET['image'])?$_GET['image']:'';
        $type = isset($_GET['type'])?$_GET['type']:'';
        $response = json_decode($instance->delete_image($slug,$image,$type));
        if($response->status==0){
            $_SESSION['error'] = $response->message;
        }
        else{
            $_SESSION['success'] = $response->message;
        }
        header("location:".CURRENT_URL.'?action=edit&slug='.$slug);exit;
    }
    else if(isset($_REQUEST['action'])&&$_REQUEST['action']=='cat_products'){
        $where = " `pm`.`category_id`='".$_REQUEST['category_id']."' ";
        $orderby[] = array('column'=>'`pm`.`name`', 'order'=>'asc');
        echo $instance->select(1,$where,$orderby);exit;
    }
	else if(isset($_REQUEST['action'])&&$_REQUEST['action']=='view'){
        $column = array(
                0=>array('column'=>'id','prefix'=>'pm'),
                1=>array('column'=>'name','prefix'=>'pm'),
                2=>array('column'=>'name','prefix'=>'cm','filter_column'=>'id','child_column'=>'category_id','child_prefix'=>'pm'),
                3=>array('column'=>'status','prefix'=>'pm')
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
            $array[] = isset($_REQUEST['order'][0]['column'])&&$_REQUEST['order'][0]['column']==0&&$_REQUEST['order'][0]['dir']=='desc'?strval($filtered_records-$start-$key):strval($start+$key);
            $array[] = $instance->db_output($val->name);
            $array[] = $instance->db_output($val->category_name);
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
    	$cat = json_decode($instance->getParents())->records;
        $filters = array();
        $filters[2] = array('label'=>'Category','data'=>$cat);   // 2 is datatable column name (column start from 0)
        $filters[3] = array('label'=>'Status','data'=>array('1'=>'Enabled','0'=>'Disabled')); // 3 is datatable column name (column start from 0)
	}
    
    $header_icon = 'fa-book text-blue';
    $header_title = 'Product Master';
    require_once(ADMIN_TEMPLATE_FILE);
?>