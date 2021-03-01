<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    class mysqli_class{
        private $link;
        private $resource;
        
        /**
         * @param string server
         * @param string database user
         * @param string database password
         * @param string database name
         * @return void
         * */
        public function __construct($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE){
            $this->link = new mysqli($server,$username,$password,$database);
        }
        
        /**
         * @param string query
         * @param int error no
         * @param string error
         * @return string
         * */
        public function db_error($query, $errno, $error) {
            $page_name = basename($_SERVER['PHP_SELF']);
    		if(DEVELOPMENT==1){
                die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[RE STOP]</font></small><br><br></b></font>');
    		}
    		else{
    			require_once("index.html");exit;
    		}
        }
        
        /**
         * @param string query
         * @return object resource
         * */
        public function db_query($query){
        	if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        	  error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
        	}
        	$_start = explode(' ', microtime());
        	$result = mysqli_query($this->link,$query) or $this->db_error($query, mysqli_errno($this->link), mysqli_error($this->link));
        	$_end = explode(' ', microtime());
        	$_time = number_format(($_end[1] + $_end[0] - ($_start[1] + $_start[0])), 8);
        	if ( defined('EXPLAIN_QUERIES') && (EXPLAIN_QUERIES == 'true') ){
        		/* Initially set to store every query */
        		$explain_this_query = true;
        		/* If the include filter is true just explain queries for those scripts */
        		if ( defined('EXPLAIN_USE_INCLUDE') && (EXPLAIN_USE_INCLUDE == 'true') ){
        			$explain_this_query = ( ( stripos( EXPLAIN_INCLUDE_FILES, basename($_SERVER['PHP_SELF']) ) ) === false ? false : true );
        		}
        		/* If the exclude filter is true just explain queries for those that are not listed */
        		if ( defined('EXPLAIN_USE_EXCLUDE') && (EXPLAIN_USE_EXCLUDE == 'true') ){
        			$explain_this_query = ( ( stripos( EXPLAIN_EXCLUDE_FILES, basename($_SERVER['PHP_SELF']) ) ) === false ? true : false );
        		}
        		/* If it still true after running it through the filters store it */
        		if ($explain_this_query) explain_query($query, $_time);
        	} # End if EXPLAIN_QUERIES
        	if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')){
        	   $result_error = mysqli_error($this->link);
        	   error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
        	}
            $this->resource = $result;
            return $result;
        }
        
        /**
         * @param void
         * @return int
         * */
        public function db_last_inserted_id(){
            return $this->link->insert_id;
        }
        
        /**
         * @param void
         * @return array
         * */
        public function db_fetch_array(){
            return $this->resource->fetch_array(MYSQLI_ASSOC);
        }
        /**
         * @param void
         * @return array
         * */
        public function db_fetch_assoc(){
            return $this->resource->fetch_assoc();
        }
        /**
         * @param void
         * @return object
         * */
        public function db_fetch_object(){
            return $this->resource->fetch_object();
        }
		/**
         * @param void
         * @return array
         * */
		public function db_fetch_all(){
			return $this->resource->fetch_all(MYSQLI_ASSOC);
		}
        /**
         * @param void
         * @return int
         * */
        public function db_num_rows(){
            return $this->resource->num_rows;
        }
		/**
		* @param void
		* @param int
		* */
		public function db_affected_rows(){
			return $this->link->affected_rows;
		}
        
        /**
         * @param string
         * @return string
         * */
        public function db_output($string){
            $string=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
            $string= stripslashes($string);
            return htmlspecialchars($string);
        }
        
        /**
         * @param string
         * @param boolean integer
         * @return string
         * */
        public function db_input($string,$int=false){
        	// Stripslashes
        	if (get_magic_quotes_gpc()){
        		$string = stripslashes($string);
        	}
        	if (!is_numeric($string)){
        		$string = mysqli_real_escape_string($this->link,$string);
        	}
        	else{
        		$string=$string;
            }
        	$string = trim($string);
            if($int===true){
                return intval($string);
            }
            else{
                return strval($string);
            }
        }
        
        /**
         * @param string table name
         * @param array data
         * @param string action, default insert
         * @param string parameter, default null
         * @return object
         * */
        public function db_perform($table, $data, $action = 'insert', $parameters = ''){
            reset($data);
            if ($action == 'insert'){
                $query = 'insert into ' . $table . ' (';
                while (list($columns, ) = each($data)) {
                    $query .= $columns . ', ';
                }
                $query = substr($query, 0, -2) . ') values (';
                reset($data);
                while (list(, $value) = each($data))
                {
                    switch ((string)$value)
                    {
                        case 'now()':
                            $query .= 'now(), ';
                            break;
                        case 'CURRENT_DATE()':
                            $query .= 'CURRENT_DATE(), ';
                            break;		  
                        case 'null':
                            $query .= 'null, ';
                            break;
                        default:
                            if(substr($this->db_input($value),0,23)=="date_add(CURRENT_DATE()") {
                                $query .= $this->db_input($value) .', ';
                            }
                            else {
                                $query .= '\'' . $this->db_input($value) . '\', ';
                            }
                            break;
                    }
                }
                $query = substr($query, 0, -2) . ')';
            }
            elseif ($action == 'update'){
              $query = 'update ' . $table . ' set ';
              while (list($columns, $value) = each($data)) {
                switch ((string)$value) {
                  case 'now()':
                    $query .= $columns . ' = now(), ';
                    break;
        		  case 'CURRENT_DATE()':
                    $query .= 'CURRENT_DATE(), ';
                    break;		  
                  case 'null':
                    $query .= $columns .= ' = null, ';
                    break;
                  default:
        		  	if(substr($this->db_input($value),0,23)=="date_add(CURRENT_DATE()")
        			{
        				$query .= $this->db_input($value) .', ';
        			}
        			else{
                    	$query .= $columns . ' = \'' . $this->db_input($value) . '\', ';
        			}
                    break;
                }
              }
              $query = substr($query, 0, -2) . ' where ' . $parameters;
            }
            return $this->db_query($query);
        }
        
        /**
         * @param string fields
         * @param string table name
         * @param string where
         * @return array
         * */
        public function get_single_value($field,$tabel,$where){
            $q = "select ".$field." from ".$tabel." where ".$where;
            $sql=$this->re_db_query($q);
        	if($this->re_db_num_rows($sql)>0){
        		$rec=$this->re_db_fetch_array($sql);
                return $rec;
        	}
        	else {
        	   return array();
            }
        }
        
        /**
         * @param void
         * @return string
         * */
        function client_ip(){
            if (isset($_SERVER)) {
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
              } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
              } else {
                $ip = $_SERVER['REMOTE_ADDR'];
              }
            } else {
              if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
              } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
              } else {
                $ip = getenv('REMOTE_ADDR');
              }
            }
            return $ip;
        }
        
        /**
         * @param void
         * @return string
         * */
        public function update_sql(){
            $user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
            $update_common_sql = " , `modified_ip`='".$this->client_ip()."', `modified_by`='".$user_id."', `modified_time`='".CURRENT_DATETIME."' ";
            return $update_common_sql;
        }
        
        /**
         * @param void
         * @return string
         * */
        public function insert_sql(){
            $user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
            $insert_common_sql = " , `created_ip`='".$this->client_ip()."', `created_by`='".$user_id."', `created_time`='".CURRENT_DATETIME."' ";
            return $insert_common_sql;
        }
        
        /**
         * @param void
         * @return string
         * */
        public function update_common_sql(){
            $update_common_sql = " , `modified_ip`='".$this->client_ip()."', `modified_by`='".$_SESSION['admin_id']."', `modified_time`='".CURRENT_DATETIME."' ";
            return $update_common_sql;
        }
        
        /**
         * @param void
         * @return string
         * */
        public function insert_common_sql(){
            $insert_common_sql = " , `created_ip`='".$this->client_ip()."', `created_by`='".$_SESSION['admin_id']."', `created_time`='".CURRENT_DATETIME."' ";
            return $insert_common_sql;
        }
		
		public function filter($request, $columns){
			$globalSearch = array();
			$columnSearch = array();
			$dtColumns = $this->pluck( $columns, 'column' );
			if ( isset($request['search']) && $request['search']['value'] != '' ) {
				$str = $request['search']['value'];
				$str = trim($str,'^');
				$str = trim($str,'$');
				$str = trim($str);
				
				for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
					$requestColumn = $request['columns'][$i];
					
					$columnIdx = array_search( $requestColumn['data'], $dtColumns );
					$column = $columns[ $columnIdx ];
					if ( isset($column['column']) && $requestColumn['searchable'] == 'true' ) {
						$globalSearch[] = "`".$column['prefix']."`.`".$column['column']."` LIKE '%".$this->db_input($str)."%'";
					}
				}
			}
			
			// Individual column filtering
			if ( isset( $request['columns'] ) ) {
				for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
					$requestColumn = $request['columns'][$i];
					$columnIdx = array_search( $requestColumn['data'], $dtColumns );
					$column = $columns[ $columnIdx ];
					//echo '<pre>';print_R($column);
					if(isset($column['column'])){
						
						$str = $requestColumn['search']['value'];
						$str = trim($str,'^');
						$str = trim($str,'$');
						$str = trim($str);
						//$requestColumn['searchable'] == 'true' && 
						if ( $str != '' ){
							$col = isset($column['filter_column'])?$column['filter_column']:$column['column'];
							if($str=='0'&&isset($column['child_column'])){
								$col = $column['child_column'];
								$column['prefix'] = $column['child_prefix'];
							}
							$columnSearch[] = "`".$column['prefix']."`.`".$col."` = '".$this->db_input($str)."'";
						}
					}
				}
			}
			// Combine the filters into a single string
			$where = '';
			if ( count( $globalSearch ) ) {
				$where = '('.implode(' OR ', $globalSearch).')';
			}
			if ( count( $columnSearch ) ) {
				$where = $where === '' ? implode(' AND ', $columnSearch) : $where .' AND '. implode(' AND ', $columnSearch);
			}
			return $where;
		}
		
		public function pluck ( $a, $prop ){
			$out = array();
			for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			  if(isset($a[$i][$prop])){
				$out[] = $i;
				}
			}
			return $out;
		}
		
		public function order ( $request, $columns, $array=false){
			$order = '';
			$orderarray = array();
			if ( isset($request['order']) && count($request['order'])>0 ) {
				$orderBy = array();
				$dtColumns = $this->pluck( $columns, 'column' );
				for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
					// Convert the column index into the column data property
					$columnIdx = intval($request['order'][$i]['column']);
					$requestColumn = $request['columns'][$columnIdx];
					$columnIdx = array_search( $requestColumn['data'], $dtColumns );
					$column = $columns[ $columnIdx ];
					if ( $requestColumn['orderable'] == 'true' ) {
						$dir = $request['order'][$i]['dir'] === 'asc' ?
							'ASC' :
							'DESC';
						
						$orderBy[] = ' ORDER BY `'.$column['column'].'` '.$dir;
						array_push($orderarray,array('column'=>$column['prefix'].'.'.$column['column'],'order'=>$dir));
					}
				}
				$order = ' '.implode(', ', $orderBy);
			}
			if($array===false){
			  return $order;
			}
			else{
				return $orderarray;
			}
		}
        
        public function slugify($str, $table, $where='') {
            $invalidSlug = true;
            $count = 1;
            $ins = new stop_words_master();
            $stopwords = explode(',',json_decode($ins->select(1),true)['records']);
    
            $str = strtolower(trim($str));
            $str = preg_replace('/[^a-zA-Z0-9-]/', ' ', $str);
            $str = trim(preg_replace('/ +/', " ", $str));
            $str = explode(" ",$str);
            $arr = array_keys(array_intersect($str,$stopwords));
            foreach($arr as $key=>$val){
                $str[$val] = '-';
            }
            $str = trim(implode(" ",$str));
            $str = trim($str,"-");
        	$str = preg_replace('/ +/', "-", $str);
            $str = preg_replace('/-+/', "-", $str);
            $str = trim($str," ");
            $slug = trim($str,"-");
            
            while($invalidSlug){
                $q = "SELECT COUNT(*) AS `total`
                        FROM `".$table."`
                        WHERE `is_delete`='0' AND `slug`='".$slug."' ".$where;
                        //echo $q;exit;
                $res = $this->db_query($q);
                $total = $this->db_fetch_object()->total;
                if($total==0){
                    $invalidSlug = false;
                }
                else{
                    $count++;
                    $slug = $slug.'-'.$count;
                }
            }
            return $slug;
        }
    }
?>