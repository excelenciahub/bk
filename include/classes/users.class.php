<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class user_master extends mysqli_class{
        private $table = USER_MASTER;
        
        /**
         * @param array post
         * @return json
         * */
        public function save($data){
            $id = isset($data['slug'])&&$data['slug']!=''?$this->db_input($data['slug']):0;
            $first_name = isset($data['first_name'])&&$data['first_name']!=''?$this->db_input($data['first_name']):'';
            $last_name = isset($data['last_name'])&&$data['last_name']!=''?$this->db_input($data['last_name']):'';
            $state_id = isset($data['state_id'])&&$data['state_id']!=''?$this->db_input($data['state_id'],true):0;
            $city_id = isset($data['city_id'])&&$data['city_id']!=''?$this->db_input($data['city_id'],true):0;
            $gst_no = isset($data['gst_no'])&&$data['gst_no']!=''?$this->db_input($data['gst_no']):'';
            $mobile_no = isset($data['mobile_no'])&&$data['mobile_no']!=''?$this->db_input($data['mobile_no']):'';
            
            $image = isset($_FILES['image'])?$_FILES['image']:array();
            $image_name = isset($_FILES['image']['name'])?$_FILES['image']['name']:'';
            $image_size = isset($_FILES['image']['size'])?$_FILES['image']['size']:'';
            $image_error = isset($_FILES['image']['error'])?$_FILES['image']['error']:0;
            $image_type = isset($_FILES['image']['type'])?$_FILES['image']['type']:'';
            $image_tmp_name = isset($_FILES['image']['tmp_name'])?$_FILES['image']['tmp_name']:'';
            
            $message = array();
            
            if($first_name==''){
                $message[] = 'Please enter first name.';
            }
            else if($last_name==''){
                $message[] = 'Please enter last name.';
            }
            else if($state_id==''){
                $message[] = 'Please select state.';
            }
            else if($city_id==''){
                $message[] = 'Please select city.';
            }
            else if($mobile_no==''){
                $message[] = 'Please enter mobile number.';
            }
            else if($image_name!='' && $image_error>0){
                $message[] = 'Image is currupted.';
            }
            else if($image_name!='' && ($image_type != 'image/jpeg' && $image_type != 'image/png')){
                $message[] = 'Please select valid image.';
            }
            else if($mobile_no==''){
                $message[] = 'Please enter mobile no.';
            }
            
            if(count($message)>0){
                $response['status'] = 0;
                $response['message'] = $message;
            }
            else{
                //Check duplicate
                $con = '';
                if($id!==0){
                    $con .= " AND `um`.`slug`!='".$id."'";
                }
                $slug = $this->slugify($first_name.' '.$last_name,$this->table," AND `slug`!='".$id."'");
                $q = "SELECT IFNULL(COUNT(*),0) AS `total`
                        FROM `".$this->table."` AS `um`
                        WHERE `um`.`mobile_no`='".$mobile_no."' AND `um`.`is_delete`='0' ".$con;
                $this->db_query($q);
                $res = $this->db_fetch_object();
                if($res->total>0){
                    $message[] = 'This mobile no is already exists.';
                }
                else if($id===0){
                    if($image_name!=''){
                        $img = time()."-".$image_name;
                        move_uploaded_file($image_tmp_name,DIR_USERS.$img);
                        
                        $resizeObj = new ResizeImage(DIR_USERS.$img);
                        $resizeObj -> resizeImage(360, 258, 'crop');
                        $resizeObj -> saveImage(DIR_USERS.get_filename($img,false).'-360x258.'.get_extension($img),DIR_USERS.$img, 100);
                        $resizeObj -> resizeImage(135, 100, 'crop');
                        $resizeObj -> saveImage(DIR_USERS.get_filename($img,false).'-135x100.'.get_extension($img),DIR_USERS.$img, 100);
                    }
                    else{
                        $img = '';
                    }
                    $q = "INSERT INTO `".$this->table."`
                            SET `first_name`='".$first_name."', `last_name`='".$last_name."', `mobile_no`='".$mobile_no."', `slug`='".$slug."', `image`='".$img."', `city_id`='".$city_id."', `gst_no`='".$gst_no."' ".$this->insert_common_sql();
                }
                else{
                    $q = "SELECT * FROM `".$this->table."` WHERE `slug`='".$id."'";
                    $res = $this->db_query($q);
                    $row = $this->db_fetch_array($res);
                    $o_image = $row['image'];
                    $c = '';
                    $uimg = false;
                    if($image_name!=''){
                        $img = time()."-".$image_name;
                        move_uploaded_file($image_tmp_name,DIR_USERS.$img);
                        
                        $resizeObj = new ResizeImage(DIR_USERS.$img);
                        $resizeObj -> resizeImage(360, 258, 'crop');
                        $resizeObj -> saveImage(DIR_USERS.get_filename($img,false).'-360x258.'.get_extension($img),DIR_USERS.$img, 100);
                        $resizeObj -> resizeImage(135, 100, 'crop');
                        $resizeObj -> saveImage(DIR_USERS.get_filename($img,false).'-135x100.'.get_extension($img),DIR_USERS.$img, 100);
                        
                        $c .= " , `image`='".$img."' ";
                    }
                    
                    $q = "UPDATE `".$this->table."`
                            SET `first_name`='".$first_name."', `last_name`='".$last_name."', `mobile_no`='".$mobile_no."', `slug`='".$slug."', `city_id`='".$city_id."', `gst_no`='".$gst_no."' ".$c." ".$this->update_common_sql()."
                            WHERE `slug`='".$id."'";
                }
                if(count($message)==0){
                    $this->db_query($q);
                    if($this->db_affected_rows()>0 || $id!==0){
                        if(isset($uimg)&&$uimg===true){
                            @unlink(DIR_USERS.$o_image);
                            @unlink(DIR_USERS.get_thumb($o_image,'360x258'));
                            @unlink(DIR_USERS.get_thumb($o_image,'135x100'));
                        }
                        $response['status'] = 1;
                        $response['message'][] = 'Record saved successfully.';
                    }
                    else{
                        $response['status'] = 0;
                        $response['message'][] = 'Something went wrong.';
                    }
                }
                else{
                    $response['status'] = 0;
                    $response['message'] = $message;
                }
            }
			return json_encode($response);
        }
        
        /**
         * @param string slug
         * @return json of records
         * */
        public function edit($slug){
            $slug = $slug!=''?$this->db_input($slug):0;
            $records = array();
            $q = "SELECT `um`.*,`sm`.`id` AS `state_id`, `sm`.`name` AS `state_name`, `sm`.`code` AS `state_code`, `cm`.`name` AS `city_name`
                    FROM `".$this->table."` AS `um`
                    LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                    LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                    WHERE `um`.`is_delete`='0' AND `um`.`slug`='".$slug."'";
            $this->db_query($q);
            if($this->db_num_rows()>0){
                $records = $this->db_fetch_object();
            }
            $response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
        
        /**
         * @param int status, default all
         * @param string where conditions, default all
         * @return array of records
         * */
        public function count($status='',$where=''){
            $con = '';
            $response = 0;
            if($status!==''){
                $con .= " AND `um`.`status`='".$this->db_input($status)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            $q = "SELECT IFNULL(count(*),0) AS `total`
                FROM `".$this->table."` AS `um`
                LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                WHERE `um`.`is_delete`='0' ".$con;
            $this->db_query($q);
            if($this->db_num_rows()>0){
                $row = $this->db_fetch_object();
                $response = $row->total;
            }
            return $response;
        }
        /**
         * @param int status, default all
         * @param string where conditions, default all
         * @param array order by, default array
         * @param int start, default none
         * @param int length, default none
         * @return json of records
         * */
        public function select($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `um`.`status`='".$this->db_input($status,true)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            if(count($orderby)>0){
                $order = " ORDER BY ";
                foreach($orderby as $key=>$val){
                    $con .= $order.$this->db_input($val['column'])." ".$this->db_input($val['order']);
                    $order = '';
                }
            }
            if($start!==''&&$length!==''){
                $con .= " LIMIT ".$this->db_input($start,true).",".$this->db_input($length,true);
            }
			
			$records = array();
            $q = "SELECT `um`.*,IFNULL(`sm`.`name`,'') AS `state_name`, IFNULL(`sm`.`code`,'') AS `state_code`,IFNULL(`cm`.`name`,'') AS `city_name`
                FROM `".$this->table."` AS `um`
                LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                WHERE `um`.`is_delete`='0' ".$con;
            $this->db_query($q);
			if($this->db_num_rows()>0){
                $records = $this->db_fetch_all();
            }
			$response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
        
        /**
         * @param int status, default all
         * @param string where conditions, default all
         * @param array order by, default array
         * @param int start, default none
         * @param int length, default none
         * @return json of available parents
         * */
        public function getCity($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `um`.`status`='".$this->db_input($status,true)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            if(count($orderby)>0){
                $order = " ORDER BY ";
                foreach($orderby as $key=>$val){
                    $con .= $order.$this->db_input($val['column'])." ".$this->db_input($val['order']);
                    $order = '';
                }
            }
            if($start!==''&&$length!==''){
                $con .= " LIMIT ".$this->db_input($start,true).",".$this->db_input($length,true);
            }
			
			$records = array();
            $q = "SELECT IFNULL(`cm`.`id`,'0') AS `id`,IFNULL(`cm`.`name`,'-') AS `name`
                FROM `".$this->table."` AS `um`
                LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                WHERE `um`.`is_delete`='0' ".$con."
                GROUP BY `um`.`city_id`";
            $this->db_query($q);
            if($this->db_num_rows()>0){
				while($row=$this->db_fetch_object()){
					$records[$row->id] = $row->name;
				}
            }
			
			$response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
        
        /**
         * @param int status, default all
         * @param string where conditions, default all
         * @param array order by, default array
         * @param int start, default none
         * @param int length, default none
         * @return json of available parents
         * */
        public function getState($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `um`.`status`='".$this->db_input($status,true)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            if(count($orderby)>0){
                $order = " ORDER BY ";
                foreach($orderby as $key=>$val){
                    $con .= $order.$this->db_input($val['column'])." ".$this->db_input($val['order']);
                    $order = '';
                }
            }
            if($start!==''&&$length!==''){
                $con .= " LIMIT ".$this->db_input($start,true).",".$this->db_input($length,true);
            }
			
			$records = array();
            $q = "SELECT IFNULL(`sm`.`id`,'0') AS `id`,IFNULL(`sm`.`name`,'-') AS `name`
                FROM `".$this->table."` AS `um`
                LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                WHERE `um`.`is_delete`='0' ".$con."
                GROUP BY `um`.`city_id`";
            $this->db_query($q);
            if($this->db_num_rows()>0){
				while($row=$this->db_fetch_object()){
					$records[$row->id] = $row->name;
				}
            }
			
			$response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
        
        /**
         * @param string slug
         * @param int status
         * @return json
         * */
        public function status($slug,$status){
            $slug = $slug!=''?$this->db_input($slug):'';
            $status = $status!=''?$this->db_input($status,true):'';
            if($slug!=='' && ($status==0 || $status==1)){
                $q = "UPDATE `".$this->table."` AS `um`
                        SET `um`.`status`='".$status."'
                        WHERE `um`.`slug`='".$slug."'";
                $this->db_query($q);
    			if($this->db_affected_rows()>0){
                    $response['status'] = 1;
                    $response['message'] = 'Record updated.';
                }
                else{
                    $response['status'] = 0;
                    $response['message'] = 'Record not updated.';
                }
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Something went wrong.';
            }
			return json_encode($response);
        }

        /**
         * @param string slug
         * @return json
         * */
        public function delete($slug){
            $slug = $slug!=''?$this->db_input($slug):'';
            if($slug!==''){
                $q = "UPDATE `".$this->table."` AS `um`
                        SET `um`.`is_delete`='1'
                        WHERE `um`.`slug`='".$slug."'";
                $this->db_query($q);
                if($this->db_affected_rows()>0){
                    $response['status'] = 1;
                    $response['message'] = 'Record deleted.';
                }
                else{
                    $response['status'] = 0;
                    $response['message'] = 'Record not deleted.';
                }
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Something went wrong.';
            }
			return json_encode($response);
        }
    }
?>