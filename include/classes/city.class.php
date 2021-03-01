<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class city_master extends mysqli_class{
        private $table = CITY_MASTER;
        
        /**
         * @param array post
         * @return json
         * */
        public function save($data){
            $id = isset($data['slug'])&&$data['slug']!=''?$this->db_input($data['slug']):0;
            $name = isset($data['name'])&&$data['name']!=''?$this->db_input($data['name']):'';
            $state_id = isset($data['state_id'])&&$data['state_id']!=''?$this->db_input($data['state_id'],true):'';
            
            $message = array();
            
            if($state_id==''){
                $message[] = 'Please select state.';
            }
            else if($name==''){
                $message[] = 'Please enter name.';
            }
            
            if(count($message)>0){
                $response['status'] = 0;
                $response['message'] = $message;
            }
            else{
                //Check duplicate
                $con = '';
                if($id!==0){
                    $con .= " AND `cm`.`slug`!='".$id."'";
                }
                $slug = $this->slugify($name,$this->table," AND `slug`!='".$id."'");
                $q = "SELECT IFNULL(COUNT(*),0) AS `total`
                        FROM `".$this->table."` AS `cm`
                        WHERE `cm`.`name`='".$name."' AND `cm`.`state_id`='".$state_id."' AND `cm`.`is_delete`='0' ".$con;
                $this->db_query($q);
                $res = $this->db_fetch_object();
                if($res->total>0){
                    $message[] = 'This name is already exists.';
                }
                else if($id===0){
                    $q = "INSERT INTO `".$this->table."`
                            SET `name`='".$name."', `state_id`='".$state_id."', `slug`='".$slug."' ".$this->insert_common_sql();
                }
                else{
                    $q = "UPDATE `".$this->table."`
                            SET `name`='".$name."', `state_id`='".$state_id."', `slug`='".$slug."' ".$this->update_common_sql()."
                            WHERE `slug`='".$id."'";
                }
                if(count($message)==0){
                    $this->db_query($q);
                    if($this->db_affected_rows()>0 || $id!==0){
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
            $q = "SELECT `cm`.*,`sm`.`name` AS `state_name`, `sm`.`code` AS `state_code`
                    FROM `".$this->table."` AS `cm`
                    LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                    WHERE `cm`.`is_delete`='0' AND `cm`.`slug`='".$slug."'";
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
                $con .= " AND `cm`.`status`='".$this->db_input($status)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            $q = "SELECT IFNULL(count(*),0) AS `total`
                FROM `".$this->table."` AS `cm`
                WHERE `cm`.`is_delete`='0' ".$con;
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
                $con .= " AND `cm`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT `cm`.*,`sm`.`name` AS `state_name`, `sm`.`code` AS `state_code`
                FROM `".$this->table."` AS `cm`
                LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                WHERE `cm`.`is_delete`='0' ".$con;
            $this->db_query($q);
			if($this->db_num_rows()>0){
                $records = $this->db_fetch_all();
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
                $q = "UPDATE `".$this->table."` AS `cm`
                        SET `cm`.`status`='".$status."'
                        WHERE `cm`.`slug`='".$slug."'";
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
                $q = "UPDATE `".$this->table."` AS `cm`
                        SET `cm`.`is_delete`='1'
                        WHERE `cm`.`slug`='".$slug."'";
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
                $con .= " AND `cm`.`status`='".$this->db_input($status,true)."' ";
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
                FROM `".$this->table."` AS `cm`
                LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`cm`.`state_id` AND `sm`.`is_delete`='0'
                WHERE `cm`.`is_delete`='0' ".$con."
                GROUP BY `cm`.`state_id`";
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
    }
?>