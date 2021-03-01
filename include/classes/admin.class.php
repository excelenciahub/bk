<?php
	class admin_master extends mysqli_class{
		
		public $table = ADMIN_MASTER;
		public $errors = '';
		
		/**
		 * @param array of username and password
		 * @return true on success, error on invalid detail
		 * */
		public function login($data){
			$username = isset($data['username'])?$this->db_input($data['username']):'';
			$password = isset($data['password'])?$this->db_input($data['password']):'';
			
			if($username==''){
				$this->errors = 'Please enter username.';
			}
			else if($password==''){
				$this->errors = 'Please enter password.';
			}
			
			if($this->errors!=''){
			     $response['status'] = 0;
				$response['message'] = $this->errors;
			}
			else{
				$q = "SELECT * FROM `".$this->table."` WHERE (`email`='".$username."' OR `username`='".$username."') AND `password`='".md5($password)."' AND `is_delete`='0'";
                $res = $this->db_query($q);
                if($this->db_num_rows($res)>0){
    				$row = $this->db_fetch_array($res);
    				if(isset($row['admin_id'])){
    					if($row['status']==1){
    						$_SESSION['admin_id'] = $row['admin_id'];
                            $_SESSION['admin_name'] = $row['first_name'].' '.$row['last_name'];
                            $_SESSION['image'] = $row['image'];
    						$response['status'] = 1;
                            $response['message'] = 'Authentication successful.';
		 	               $response['admin_id'] = $row['admin_id'];
                           $response['admin_name'] = $row['first_name'].' '.$row['last_name'];
    					}
    					else{
                            $response['status'] = 0;
			 	            $response['message'] = 'Your account is disabled.';
    					}
    				}
                    else{
                        $response['status'] = 0;
    			 	    $response['message'] = 'Invalid detail.';
    				}
                }
				else{
                    $response['status'] = 0;
			 	    $response['message'] = 'Invalid detail.';
				}
			}
            return json_encode($response);
		} 
        
        public function change_password($data){
            $current_password = isset($data['current_password'])?$this->db_input($data['current_password']):'';
            $new_password = isset($data['new_password'])?$this->db_input($data['new_password']):'';
            $confirm_password = isset($data['confirm_password'])?$this->db_input($data['confirm_password']):'';
            $response = array();
            if($current_password==''){
                $this->errors = 'Please enter old password.';
            }
            else if($new_password==''){
                $this->errors = 'Please enter new password.';
            }
            else if($confirm_password==''){
                $this->errors = 'Please enter confirm password.';
            }
            else if($new_password != $confirm_password){
                $this->errors = 'Password and confirm password must be same.';
            }
            if($this->errors!=''){
                $response['status'] = 0;
				$response['message'] = $this->errors;
            }
            else{
                $q = "SELECT * FROM `".$this->table."` WHERE `admin_id`='".$_SESSION['admin_id']."' AND `password`='".md5($current_password)."' AND `is_delete`='0'";
                $this->db_query($q);
                if($this->db_num_rows()>0){
                    $q = "UPDATE `".$this->table."` SET `password`='".md5($confirm_password)."' ".$this->update_common_sql()." WHERE `admin_id`='".$_SESSION['admin_id']."'";
                    $this->db_query($q);
    				if($this->db_affected_rows()>0){
    				    $response['status'] = 1;
				        $response['message'] = 'Record updated successfully.';
    				}
    				else{
    					$response['status'] = 0;
				        $response['message'] = 'Something went wrong, Please try again.';
    				}
                }
                else{
                    $response['status'] = 0;
				    $response['message'] = 'Invalid current password.';
                }
            }
            return json_encode($response);
        }     
	}
?>