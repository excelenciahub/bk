<?php
    require_once("../include/config.php");
    
    $response = json_decode(is_login('admin'));
    if($response->status==0){
        echo json_encode($response);exit;
    }
    require_once(DIR_CLASSES."dumper.class.php");
    
    try {
    	$world_dumper = Shuttle_Dumper::create(array(
    		'host' => DB_SERVER,
    		'username' => DB_SERVER_USERNAME,
    		'password' => DB_SERVER_PASSWORD,
    		'db_name' => DB_DATABASE,
    	));
    
    	// dump the database to gzipped file
    	//$world_dumper->dump('world.sql.gz');
    
    	// dump the database to plain text file
        
        $datetime = date("Y-m-d H:i:s");
        $date = date("Y-m-d",strtotime($datetime));
        $filename = date('dmYHis',strtotime($datetime)).'.sql';
        
    	$world_dumper->dump(BACKUP_BASEPATH.$filename);
        $_SESSION['success'] = 'Database export success.';
        
        $created_by = isset($_SESSION['admin_id'])?$_SESSION['admin_id']:0;
        $created_ip = $mins->client_ip();
        $q = "INSERT INTO `".DB_EXPORTS."` SET `filename`='".$filename."', `date`='".$date."', `created_time`='".$datetime."', `created_ip`='".$created_ip."', `created_by`='".$created_by."'";
        $mins->db_query($q);
    }
    catch(Shuttle_Exception $e) {
    	$_SESSION['error'] =  "Couldn't dump database: " . $e->getMessage();
    }
    header("location:".$_SERVER['HTTP_REFERER']);exit;
?>