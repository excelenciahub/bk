<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }

    class order_master extends mysqli_class{
        private $table = ORDER_MASTER;
        
        /**
         * @param array post
         * @return json
         * */
        public function save($data){
            $id = isset($data['invoice'])&&$data['invoice']!=''?$this->db_input($data['invoice']):0;
            $user_id = isset($data['user_id'])&&$data['user_id']!=''?$this->db_output($data['user_id']):'';
            $payment_type = isset($data['payment_type'])&&$data['payment_type']!=''?$this->db_output($data['payment_type']):'';
            $payment_date = isset($data['payment_date'])&&$data['payment_date']!=''?$this->db_output($data['payment_date']):'';
            $note = isset($data['note'])&&$data['note']!=''?$this->db_output($data['note']):'';
            $products = isset($data['products'])&&$data['products']!=''?$data['products']:array();
            
            $message = array();
            
            if($user_id==''){
                $message[] = 'Please select user.';
            }
            else if($payment_type==''){
                $message[] = 'Please select payment type.';
            }
            else if($payment_date==''&&$payment_type==2){
                $message[] = 'Please select payment date.';
            }
            else if(count($products)==0){
                $message[] = 'Please add products.';
            }
            else{
                foreach($products as $key=>$val){
                    if($val['category_id']==''||$val['product_id']==''||$val['qty']==''||!is_numeric($val['qty'])){
                        $message[] = 'Please select category, product and quantity properly.';
                    }
                }
            }
            
            if(count($message)>0){
                $response['status'] = 0;
                $response['message'] = $message;
            }
            else{
                $pro_instance = new product_master();
                $total_amount = 0;
                $total_qty = 0;
                $payment_status = 0;
                $product_prices = array();
                foreach($products as $key=>$val){
                    $product_slug = $pro_instance->getSlug($val['product_id']);
                    $product = json_decode($pro_instance->edit($product_slug))->records;
                    $price = 0;
                    foreach($product->price as $k=>$v){
                        if($val['qty']>=$v->min_qty&&($val['qty']<=$v->max_qty||$v->max_qty==0)){
                            if($payment_type==1){
                                $price = $v->instant_price;
                                $payment_status = 1;
                            }
                            else if($payment_type==2){
                                $price = $v->future_price;
                            }
                            break;
                        }
                    }
                    if($price==0){
                        foreach($product->price as $k=>$v){
                            if($val['qty']>=$v->min_qty){
                                if($payment_type==1){
                                    $price = $v->instant_price;
                                    $payment_status = 1;
                                }
                                else if($payment_type==2){
                                    $price = $v->future_price;
                                }
                                break;
                            }
                        }
                    }
                    $products[$key]['prices'] = json_encode($product->price);
                    $products[$key]['price'] = $price;
                    $products[$key]['payable_amount'] = $price*$val['qty'];
                    $total_amount = $total_amount+$products[$key]['payable_amount'];
                    $total_qty = $total_qty+$val['qty'];
                }
                $payment_date = $payment_type==1?date("d-m-Y"):$payment_date;
                $payment_payed_date = $payment_type==1?date("d-m-Y"):$payment_date;
                
                if($id===0){
                    $invoice = $this->generate_invoice();
                    $q = "INSERT INTO `".$this->table."`
                            SET `invoice`='".$invoice."', `user_id`='".$user_id."', `total_products`='".count($products)."', `total_qty`='".$total_qty."', `payment_type`='".$payment_type."', `payment_date`='".format_date($payment_date)."', `payment_payed_date`='".format_date($payment_payed_date)."', `total_amount`='".$total_amount."', `payment_status`='".$payment_status."', `note`='".$note."' ".$this->insert_common_sql();
                }
                else{
                    $q = "UPDATE `".$this->table."`
                            SET `user_id`='".$user_id."', `total_products`='".count($products)."', `total_qty`='".$total_qty."', `payment_type`='".$payment_type."', `payment_date`='".format_date($payment_date)."', `payment_payed_date`='".format_date($payment_payed_date)."', `total_amount`='".$total_amount."', `payment_status`='".$payment_status."', `note`='".$note."' ".$this->update_common_sql()."
                            WHERE `invoice`='".$id."'";
                }
                if(count($message)==0){
                    $this->db_query($q);
                    if($this->db_affected_rows()>0 || $id!==0){
                        if($id===0){
                            $id = $this->db_last_inserted_id();
                        }
                        else{
                            $id = $this->getId($id);
                            $q = "UPDATE `".ORDER_PRODUCTS."` SET `is_delete`='1' ".$this->update_common_sql()." WHERE `order_id`='".$id."' ";
                            $this->db_query($q);
                        }
                        foreach($products as $key=>$val){
                            $q = "INSERT INTO `".ORDER_PRODUCTS."` SET `order_id`='".$id."', `product_id`='".$val['product_id']."', `qty`='".$val['qty']."', `price`='".$val['price']."', `payable_amount`='".$val['payable_amount']."', `prices`='".$val['prices']."' ".$this->insert_common_sql();
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
        
        public function update($data){
            $invoice = isset($data['invoice'])&&$data['invoice']!=''?$data['invoice']:'';
            $payment_type = isset($data['payment_type'])&&$data['payment_type']!=''?$data['payment_type']:'';
            $qty = isset($data['qty'])&&$data['qty']!=''?$data['qty']:array();
            $response = array();
            $total_qty = 0;
            $total_amount = 0;
            foreach($qty as $key=>$val){
                if($val==''){
                    $response['status'] = 0;
                    $response['message'] = 'Please enter qty.';
                    break;
                }
                $total_qty = $total_qty+$val;
                $q = "SELECT `prices` FROM `".ORDER_PRODUCTS."` WHERE `id`='".$key."'";
                $this->db_query($q);
                $record = json_decode($this->db_fetch_object()->prices);
                $price = 0;
                foreach($record as $k=>$v){
                    if($val>=$v->min_qty&&($val<=$v->max_qty||$v->max_qty==0)){
                        if($payment_type==1){
                            $price = $v->instant_price;
                            $payment_status = 1;
                        }
                        else if($payment_type==2){
                            $price = $v->future_price;
                        }
                        break;
                    }
                }
                if($price==0){
                    foreach($record as $k=>$v){
                        if($val>=$v->min_qty){
                            if($payment_type==1){
                                $price = $v->instant_price;
                                $payment_status = 1;
                            }
                            else if($payment_type==2){
                                $price = $v->future_price;
                            }
                            break;
                        }
                    }
                }
                $payable_amount = $price*$val;
                $total_amount = $total_amount+$payable_amount;
                
                $q = "UPDATE `".ORDER_PRODUCTS."` SET `qty`='".$val."', `price`='".$price."', `payable_amount`='".$payable_amount."' ".$this->update_common_sql()." WHERE `id`='".$key."'";
                $this->db_query($q);
            }
            if(count($response)==0){
                $q = "UPDATE `".$this->table."` SET `total_qty`='".$total_qty."', `total_amount`='".$total_amount."' ".$this->update_common_sql()." WHERE `invoice`='".$invoice."'";
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
            return json_encode($response);
        }
        
        /**
         * @param string slug
         * @return json of records
         * */
        public function edit($invoice){
            $invoice = $invoice!=''?$this->db_input($invoice):0;
            $record = array();
            $q = "SELECT `om`.*,`op`.`id` AS `order_item_id`,`op`.`product_id`,`op`.`qty`,`op`.`price`,`op`.`payable_amount`,`op`.`prices`,`um`.`first_name` AS `user_first_name`,`um`.`last_name` AS `user_last_name`,`um`.`mobile_no`,`um`.`gst_no` AS `user_gst_no`,`pm`.`name` AS `product_name`,`cm`.`name` AS `category_name`,`cm`.`id` AS `category_id`,IFNULL(`ctm`.`name`,'') AS `city_name`,IFNULL(`sm`.`name`,'') AS `state_name`,IFNULL(`sm`.`code`,'') AS `state_code`
                    FROM `".$this->table."` AS `om`
                    LEFT JOIN `".USER_MASTER."` AS `um` ON `um`.`id`=`om`.`user_id` AND `um`.`is_delete`='0'
                    LEFT JOIN `".ORDER_PRODUCTS."` AS `op` ON `op`.`order_id`=`om`.`id` AND `op`.`is_delete`='0'
                    LEFT JOIN `".PRODUCT_MASTER."` AS `pm` ON `pm`.`id`=`op`.`product_id` AND `pm`.`is_delete`='0'
                    LEFT JOIN `".CATEGORY_MASTER."` AS `cm` ON `cm`.`id`=`pm`.`category_id` AND `cm`.`is_delete`='0'
                    LEFT JOIN `".CITY_MASTER."` AS `ctm` ON `ctm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                    LEFT JOIN `".STATE_MASTER."` AS `sm` ON `sm`.`id`=`ctm`.`state_id` AND `cm`.`is_delete`='0'
                    WHERE `om`.`is_delete`='0' AND `om`.`invoice`='".$invoice."'";
            $this->db_query($q);
            $products = array();
            if($this->db_num_rows()>0){
                $records = $this->db_fetch_all();
                foreach($records as $key=>$val){
                    $record = $val;
                    $products[] = array(
                                        'order_item_id'=>$val['order_item_id'],
                                        'product_id'=>$val['product_id'],
                                        'product_name'=>$val['product_name'],
                                        'category_id'=>$val['category_id'],
                                        'category_name'=>$val['category_name'],
                                        'qty'=>$val['qty'],
                                        'price'=>$val['price'],
                                        'payable_amount'=>$val['payable_amount'],
                                        'prices'=>$val['prices'],
                                    );
                }
                $record['products'] = $products;
            }
            $response['total'] = $this->db_num_rows();
			$response['records'] = $record;
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
                $con .= " AND `om`.`status`='".$this->db_input($status)."' ";
            }
            if($where!==''){
                $con .= " AND ".$where." ";
            }
            $q = "SELECT IFNULL(count(*),0) AS `total`
                    FROM `".$this->table."` AS `om`
                    LEFT JOIN `".USER_MASTER."` AS `um` ON `um`.`id`=`om`.`user_id` AND `um`.`is_delete`='0'
                    WHERE `om`.`is_delete`='0' ".$con;
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
                $con .= " AND `om`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT `om`.*,`um`.`first_name` AS `user_first_name`,`um`.`last_name` AS `user_last_name`,`um`.`mobile_no`,IFNULL(`cm`.`name`,'') AS `city_name`
                    FROM `".$this->table."` AS `om`
                    LEFT JOIN `".USER_MASTER."` AS `um` ON `um`.`id`=`om`.`user_id` AND `um`.`is_delete`='0'
                    LEFT JOIN `".CITY_MASTER."` AS `cm` ON `cm`.`id`=`um`.`city_id` AND `cm`.`is_delete`='0'
                    WHERE `om`.`is_delete`='0' ".$con;
            $this->db_query($q);
			if($this->db_num_rows()>0){
                $records = $this->db_fetch_all();
            }
            //printr($records);
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
        public function getProducts($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `om`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT IFNULL(`pm`.`id`,'0') AS `id`,IFNULL(`pm`.`name`,'-') AS `name`
                FROM `".$this->table."` AS `om`
                LEFT JOIN `".ORDER_PRODUCTS."` AS `sp` ON `sp`.`order_id`=`om`.`id` AND `sp`.`is_delete`='0'
                LEFT JOIN `".PRODUCT_MASTER."` AS `pm` ON `pm`.`id`=`sp`.`product_id` AND `pm`.`is_delete`='0'
                WHERE `om`.`is_delete`='0' ".$con."
                GROUP BY `sp`.`product_id`";
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
        public function getUsers($status='',$where='',$orderby=array(),$start='',$length=''){
            $con = '';
            if($status!==''){
                $con .= " AND `om`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT IFNULL(`um`.`id`,'0') AS `id`,IFNULL(CONCAT(`um`.`first_name`,' ',`um`.`last_name`),'-') AS `name`
                FROM `".$this->table."` AS `om`
                LEFT JOIN `".USER_MASTER."` AS `um` ON `um`.`id`=`om`.`user_id` AND `um`.`is_delete`='0'
                WHERE `om`.`is_delete`='0' ".$con."
                GROUP BY `om`.`user_id`";
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
        public function payment_status($invoice,$status){
            $invoice = $invoice!=''?$this->db_input($invoice):'';
            $status = $status!=''?$this->db_input($status,true):'';
            if($invoice!=='' && ($status==0 || $status==1)){
                $con = "";
                if($status==1){
                    $con .= " , `om`.`payment_payed_date`='".date("Y-m-d")."' ";
                }
                else{
                    $con .= " , `om`.`payment_payed_date`=`om`.`payment_date` ";
                }
                $q = "UPDATE `".$this->table."` AS `om`
                        SET `om`.`payment_status`='".$status."' ".$con."
                        WHERE `om`.`invoice`='".$invoice."'";
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
         * @param int status
         * @return json
         * */
        public function status($invoice,$status){
            $invoice = $invoice!=''?$this->db_input($invoice):'';
            $status = $status!=''?$this->db_input($status,true):'';
            if($invoice!=='' && ($status==0 || $status==1)){
                $q = "UPDATE `".$this->table."` AS `om`
                        SET `om`.`status`='".$status."'
                        WHERE `om`.`invoice`='".$invoice."'";
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
        public function delete($invoice){
            $invoice = $invoice!=''?$this->db_input($invoice):'';
            if($invoice!==''){
                $q = "UPDATE `".$this->table."` AS `om`
                        SET `om`.`is_delete`='1'
                        WHERE `om`.`invoice`='".$invoice."'";
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
        
        public function generate_invoice(){
           $count = INVOICE+1;
           $s = str_pad($count, 5, '0', STR_PAD_LEFT);
           
           $q = "UPDATE `".SITE_CONFIG."` SET `value`='".$count."' WHERE `option`='INVOICE' ";
           $this->db_query($q);
           
           $invoice = 'SP'.$s;
           return $invoice;
        }
        
        public function getId($invoice){
            $q = "SELECT `om`.`id`
                    FROM `".$this->table."` AS `om`
                    WHERE `om`.`invoice`='".$invoice."' AND `om`.`is_delete`='0'";
            $this->db_query($q);
            $response = $this->db_fetch_object();
            return $response->id;
        }
    }
?>