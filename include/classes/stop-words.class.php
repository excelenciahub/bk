<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class stop_words_master extends mysqli_class{
        private $table = STOP_WORDS_MASTER;
        
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
                $con .= " AND `sw`.`status`='".$this->db_input($status,true)."' ";
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
            $q = "SELECT GROUP_CONCAT(LOWER(`sw`.`word`)) AS `words`
                FROM `".$this->table."` AS `sw`
                WHERE `sw`.`is_delete`='0' ".$con;
            $this->db_query($q);
			if($this->db_num_rows()>0){
                $records = $this->db_fetch_object()->words;
            }
			$response['total'] = $this->db_num_rows();
			$response['records'] = $records;
			return json_encode($response);
        }
    }
?>