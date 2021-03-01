<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class product_master extends mysqli_class{
        private $table = PRODUCT_MASTER;
        
        /**
         * @param array post
         * @return json
         * */
        public function save($data){
            $id = isset($data['slug'])&&$data['slug']!=''?$this->db_input($data['slug']):0;
            $name = isset($data['name'])&&$data['name']!=''?$this->db_input($data['name']):'';
            $category_id = isset($data['category_id'])&&$data['category_id']!=''?$this->db_input($data['category_id'],true):0;
            $price = isset($data['price'])&&$data['price']!=''?$data['price']:array();
            
            $image = isset($_FILES['image'])?$_FILES['image']:array();
            $image_name = isset($_FILES['image']['name'])?$_FILES['image']['name']:'';
            $image_size = isset($_FILES['image']['size'])?$_FILES['image']['size']:'';
            $image_error = isset($_FILES['image']['error'])?$_FILES['image']['error']:0;
            $image_type = isset($_FILES['image']['type'])?$_FILES['image']['type']:'';
            $image_tmp_name = isset($_FILES['image']['tmp_name'])?$_FILES['image']['tmp_name']:'';
            
            $other_images = isset($_FILES['other_image'])?$_FILES['other_image']:array();
            if(count($other_images)>0){
                $other_images = reArray($other_images,'name');
            }
            
            $message = array();
            
            if($category_id==0){
                $message[] = 'Please enter select category.';
            }
            else if($name==''){
                $message[] = 'Please enter product name.';
            }
            else if($image_name!='' && $image_error>0){
                $message[] = 'Image is currupted.';
            }
            else if($image_name!='' && ($image_type != 'image/jpeg' && $image_type != 'image/png')){
                $message[] = 'Please select valid image.';
            }
            else if(count($price)==0){
                $message[] = 'Please enter price.';
            }
            else if(count($other_images)>0){
                foreach($other_images as $key=>$val){
                    if($val['name']!=''){
                        if($val['error']>0){
                            $message[] = 'Other image is currupted.';
                            break;
                        }
                        else if($val['type'] != 'image/jpeg' && $val['type'] != 'image/png'){
                            $message[] = 'Please select valid other images.';
                            break;
                        }
                    }
                }
            }
            if(count($message)==0){
                foreach($price as $key=>$val){
                    $val['max_qty'] = $val['max_qty']!=''?$val['max_qty']:0;
                    if($val['min_qty']==''||$val['instant_price']==''||$val['future_price']==''||!is_numeric($val['min_qty'])||!is_numeric($val['max_qty'])||!is_numeric($val['instant_price'])||!is_numeric($val['future_price'])){
                        printr($val);
                        $message[] = 'Please enter price and qty properly.';
                        break;
                    }
                }
            }
            
            if(count($message)>0){
                $response['status'] = 0;
                $response['message'] = $message;
            }
            else{
                //Check duplicate
                $con = '';
                if($id!==0){
                    $con .= " AND `pm`.`slug`!='".$id."'";
                }
                $slug = $this->slugify($name,$this->table," AND `slug`!='".$id."'");
                $q = "SELECT IFNULL(COUNT(*),0) AS `total`
                        FROM `".$this->table."` AS `pm`
                        WHERE `pm`.`name`='".$name."' AND `pm`.`is_delete`='0' AND `pm`.`category_id`='".$category_id."' ".$con;
                $this->db_query($q);
                $res = $this->db_fetch_object();
                if($res->total>0){
                    $message[] = 'This product is already exists.';
                }
                else if($id===0){
                    if($image_name!=''){
                        $img = time()."-".$image_name;
                        move_uploaded_file($image_tmp_name,DIR_PRODUCTS.$img);
                        
                        $resizeObj = new ResizeImage(DIR_PRODUCTS.$img);
                        $resizeObj -> resizeImage(360, 258, 'crop');
                        $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($img,false).'-360x258.'.get_extension($img),DIR_PRODUCTS.$img, 100);
                        $resizeObj -> resizeImage(135, 100, 'crop');
                        $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($img,false).'-135x100.'.get_extension($img),DIR_PRODUCTS.$img, 100);
                    }
                    else{
                        $img = '';
                    }
                    
                    $otherimg = array();
                    if(count($other_images)>0){
                        foreach($other_images as $key=>$val){
                            if($val['name']!=''){
                                $oimg = time()."_".$val['name'];
                                move_uploaded_file($val['tmp_name'],DIR_PRODUCTS.$oimg);
                                $otherimg[] = $oimg;
                                
                                $resizeObj = new ResizeImage(DIR_PRODUCTS.$oimg);
                                $resizeObj -> resizeImage(360, 258, 'crop');
                                $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($oimg,false).'-360x258.'.get_extension($oimg),DIR_PRODUCTS.$oimg, 100);
                                $resizeObj -> resizeImage(135, 100, 'crop');
                                $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($oimg,false).'-135x100.'.get_extension($oimg),DIR_PRODUCTS.$oimg, 100);
                            }
                        }
                    }
                    
                    $q = "INSERT INTO `".$this->table."`
                            SET `name`='".$name."', `slug`='".$slug."', `image`='".$img."', `other_images`='".json_encode($otherimg)."', `category_id`='".$category_id."' ".$this->insert_common_sql();
                }
                else{
                    $q = "SELECT * FROM `".$this->table."` WHERE `slug`='".$id."'";
                    $res = $this->db_query($q);
                    $row = $this->db_fetch_array($res);
                    $o_image = $row['image'];
                    $o_otherimg = $row['other_images'];
                    $pid = $row['id'];
                    $uimg = false;
                    
                    $c = '';
                    if($image_name!=''){
                        $img = time()."-".$image_name;
                        move_uploaded_file($image_tmp_name,DIR_PRODUCTS.$img);
                        
                        $resizeObj = new ResizeImage(DIR_PRODUCTS.$img);
                        $resizeObj -> resizeImage(360, 258, 'crop');
                        $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($img,false).'-360x258.'.get_extension($img),DIR_PRODUCTS.$img, 100);
                        $resizeObj -> resizeImage(135, 100, 'crop');
                        $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($img,false).'-135x100.'.get_extension($img),DIR_PRODUCTS.$img, 100);
                        
                        $c .= " , `image`='".$img."' ";
                        $uimg = true;
                    }
                    
                    $otherimg = json_decode($o_otherimg,true);
                    if(count($other_images)>0){
                        foreach($other_images as $key=>$val){
                            if($val['name']!=''){
                                $oimg = time()."_".$val['name'];
                                move_uploaded_file($val['tmp_name'],DIR_PRODUCTS.$oimg);
                                $otherimg[] = $oimg;
                                
                                $resizeObj = new ResizeImage(DIR_PRODUCTS.$oimg);
                                $resizeObj -> resizeImage(360, 258, 'crop');
                                $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($oimg,false).'-360x258.'.get_extension($oimg),DIR_PRODUCTS.$oimg, 100);
                                
                                $resizeObj -> resizeImage(135, 100, 'crop');
                                $resizeObj -> saveImage(DIR_PRODUCTS.get_filename($oimg,false).'-135x100.'.get_extension($oimg),DIR_PRODUCTS.$oimg, 100);
                                
                                
                            }
                        }
                    }
                    
                    $q = "UPDATE `".$this->table."`
                            SET `name`='".$name."', `slug`='".$slug."', `other_images`='".json_encode($otherimg)."', `category_id`='".$category_id."' ".$c." ".$this->update_common_sql()."
                            WHERE `slug`='".$id."'";
                }
                if(count($message)==0){
                    $this->db_query($q);
                    if($this->db_affected_rows()>0 && $id===0){ 
                        $id = $this->db_last_inserted_id();
                        
                        foreach($price as $key=>$val){
                            $val['max_qty'] = $val['max_qty']==''?0:$val['max_qty'];
                            $q = "INSERT INTO `".PRODUCT_PRICE."` SET `product_id`='".$id."', `min_qty`='".$val['min_qty']."', `max_qty`='".$val['max_qty']."', `instant_price`='".$val['instant_price']."', `future_price`='".$val['future_price']."' ".$this->insert_common_sql();
                            $this->db_query($q);
                        }
                        $response['status'] = 1;
                        $response['message'][] = 'Record saved successfully.';
                    }
                    else if($this->db_affected_rows()>0){
                        $q = "UPDATE `".PRODUCT_PRICE."` SET `is_delete`='1' ".$this->update_common_sql()." WHERE `product_id`='".$pid."'";
                        $this->db_query($q);
                        if($uimg===true){
                            @unlink(DIR_PRODUCTS.$o_image);
                            @unlink(DIR_PRODUCTS.get_thumb($o_image,'360x258'));
                            @unlink(DIR_PRODUCTS.get_thumb($o_image,'135x100'));
                        }
                        foreach($price as $key=>$val){
                            $val['max_qty'] = $val['max_qty']==''?0:$val['max_qty'];
                            $q = "INSERT INTO `".PRODUCT_PRICE."` SET `product_id`='".$pid."', `min_qty`='".$val['min_qty']."', `max_qty`='".$val['max_qty']."', `instant_price`='".$val['instant_price']."', `future_price`='".$val['future_price']."' ".$this->insert_common_sql();
                            $this->db_query($q);
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
            $q = "SELECT `pm`.*,`cm`.`name` AS `category_name`,`pp`.`min_qty`,`pp`.`max_qty`,`pp`.`instant_price`,`pp`.`future_price`
                    FROM `".$this->table."` AS `pm`
                    LEFT JOIN `".CATEGORY_MASTER."` AS `cm` ON `cm`.`id`=`pm`.`category_id` AND `cm`.`is_delete`='0'
                    LEFT JOIN `".PRODUCT_PRICE."` as `pp` ON `pp`.`product_id`=`pm`.`id` AND `pp`.`is_delete`='0'
                    WHERE `pm`.`is_delete`='0' AND `pm`.`slug`='".$slug."'";
            $this->db_query($q);
            if($this->db_num_rows()>0){
                $res = $this->db_fetch_all();
                foreach($res as $key=>$val){
                    if(count($records)==0){
                        $records = $val;
                    }
                    $records['price'][] = array(
                                                'min_qty'=>$val['min_qty'],
                                                'max_qty'=>$val['max_qty'],
                                                'instant_price'=>$val['instant_price'],
                                                'future_price'=>$val['future_price'],
                                            );
                }
            }
            $response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
        
        public function getSlug($id){
            $q = "SELECT `pm`.`slug` 
                    FROM `".$this->table."` AS `pm`
                    WHERE `pm`.`id`='".$id."'";
            $this->db_query($q);
            $response = $this->db_fetch_object();
            return $response->slug;
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
                $con .= " AND `pm`.`status`='".$this->db_input($status)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            
            $q = "SELECT IFNULL(count(*),0) AS `total`
                FROM `".$this->table."` AS `pm`
                LEFT JOIN `".CATEGORY_MASTER."` AS `cm` ON `cm`.`id`=`pm`.`category_id` AND `cm`.`is_delete`='0'
                WHERE `pm`.`is_delete`='0' ".$con;
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
                $con .= " AND `pm`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT `pm`.*,IFNULL(`cm`.`name`,'') AS `category_name`,`pp`.`min_qty`,`pp`.`max_qty`,`pp`.`instant_price`,`pp`.`future_price`
                FROM `".$this->table."` AS `pm`
                LEFT JOIN `".CATEGORY_MASTER."` AS `cm` ON `cm`.`id`=`pm`.`category_id` AND `cm`.`is_delete`='0'
                LEFT JOIN `".PRODUCT_PRICE."` as `pp` ON `pp`.`product_id`=`pm`.`id` AND `pp`.`is_delete`='0'
                WHERE `pm`.`is_delete`='0' ".$con;
            $this->db_query($q);
			if($this->db_num_rows()>0){
                $res = $this->db_fetch_all();
                foreach($res as $key=>$val){
                    if(!isset($records[$val['id']])){
                        $records[$val['id']] = $val;
                    }
                    $records[$val['id']]['price'][] = array(
                                                            'min_qty'=>$val['min_qty'],
                                                            'max_qty'=>$val['max_qty'],
                                                            'instant_price'=>$val['instant_price'],
                                                            'future_price'=>$val['future_price'],
                                                        );
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
        public function getParents($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `pm`.`status`='".$this->db_input($status,true)."' ";
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
                FROM `".$this->table."` AS `pm`
                LEFT JOIN `".CATEGORY_MASTER."` AS `cm` ON `cm`.`id`=`pm`.`category_id` AND `cm`.`is_delete`='0'
                WHERE `pm`.`is_delete`='0' ".$con."
                GROUP BY `pm`.`category_id`";
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
                $q = "UPDATE `".$this->table."` AS `pm`
                        SET `pm`.`status`='".$status."'
                        WHERE `pm`.`slug`='".$slug."'";
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
                $q = "UPDATE `".$this->table."` AS `pm`
                        SET `pm`.`is_delete`='1'
                        WHERE `pm`.`slug`='".$slug."'";
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
        
        public function delete_image($slug,$image,$type){
            $q = "SELECT * FROM `".$this->table."` WHERE `slug`='".$slug."'";
            $res = $this->db_query($q);
            $row = $this->db_fetch_array($res);
            if($type=='main'){
                $q = "UPDATE `".$this->table."` SET `image`='' WHERE `slug`='".$slug."'";
                @unlink(DIR_PRODUCTS.$image);
                @unlink(DIR_PRODUCTS.get_thumb($image,'360x258'));
                @unlink(DIR_PRODUCTS.get_thumb($image,'135x100'));
            }
            else{
                $o_images = json_decode($row['other_images'],true);
                
                if (($key = array_search($image, $o_images)) !== false) {
                    @unlink(DIR_PRODUCTS.$o_images[$key]);
                    @unlink(DIR_PRODUCTS.get_thumb($o_images[$key],'360x258'));
                    @unlink(DIR_PRODUCTS.get_thumb($o_images[$key],'135x100'));
                    unset($o_images[$key]);
                }
                
                $q = "UPDATE `".$this->table."` SET `other_images`='".json_encode(array_values($o_images))."' WHERE `slug`='".$slug."'";
            }
            $this->db_query($q);
            if($this->db_affected_rows()>0){
                $response['status'] = 1;
                $response['message'] = 'Record updated.';
            }
            else{
                $response['status'] = 0;
                $response['message'] = 'Record not updated.';
            }
            return json_encode($response);
        }
    }
?>