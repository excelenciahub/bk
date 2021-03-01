<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    
    define('ACTION',isset($_REQUEST['action'])?$_REQUEST['action']:'');
    define('CURRENT_URL',strtok(SSL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'?'));
    define('CURRENT_DATETIME',date('Y-m-d H:i:s'));
    define('FILENAME',basename($_SERVER['SCRIPT_FILENAME']));
    define('CONTENT',substr(FILENAME,0,strrpos(FILENAME,'.')).'.tpl.php');
    
    define('DIR_UPLOADS',BASEPATH.'uploads/');
    define('SITE_UPLOADS', SITE_URL.'uploads/');
    
    define('DIR_CATEGORY',DIR_UPLOADS.'category/');
    define('SITE_CATEGORY', SITE_UPLOADS.'category/');
    
    define('DIR_PRODUCTS',DIR_UPLOADS.'products/');
    define('SITE_PRODUCTS', SITE_UPLOADS.'products/');
    
    define('DIR_USERS',DIR_UPLOADS.'users/');
    define('SITE_USERS', SITE_UPLOADS.'users/');
    
    $mins = new mysqli_class();
    $qry = "SELECT * FROM `".SITE_CONFIG."`";
    $mins->db_query($qry);
	if($mins->db_num_rows()>0){
        $records = $mins->db_fetch_all();
        foreach($records as $k=>$v){
            $key = strtoupper($v['option']);
            if (!defined($key)){
                define($key,$v['value']);
            }
        }
    }
    
    if(CURRENT_URL==SITE_URL.'include/config.php'){
        require_once("index.html");exit;
    }
?>