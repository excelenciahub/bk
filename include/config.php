<?php @session_start();
	date_default_timezone_set("Asia/Kolkata");
    
    ini_set('display_errors',0);

    define('BASE_DIR','/bk/');
    define('SSL',(isset($_SERVER['HTTPS']) ? "https" : "http")."://");
	define('HTTP_SERVER', SSL.$_SERVER['HTTP_HOST'].'/'); 
	define('ENABLE_SSL', false);
    define('DEVELOPMENT',0);
    
    define('SITE_URL', SSL.$_SERVER['HTTP_HOST'].BASE_DIR);
    define('ADMIN_URL', SSL.$_SERVER['HTTP_HOST'].BASE_DIR.'admin/');
    
	define('BASEPATH',$_SERVER['DOCUMENT_ROOT'].BASE_DIR);
	define('DIR_INCLUDES',BASEPATH.'include/');
    define('DIR_CLASSES',DIR_INCLUDES.'classes/');
	define('DIR_TEMPLATES', BASEPATH.'templates/');
	define('DIR_CONTENT', DIR_TEMPLATES.'content/');
    define('DIR_TEMPLATE_FILE',DIR_TEMPLATES.'main.tpl.php');
    
    define('SITE_ASSETS', SITE_URL.'assets/');
    define('SITE_CSS', SITE_ASSETS.'css/');
    define('SITE_JS', SITE_ASSETS.'js/');
    define('SITE_IMAGES', SITE_ASSETS.'images/');
    define('SITE_PLUGINS', SITE_ASSETS.'plugins/');
    
    define('ADMIN_BASEPATH',$_SERVER['DOCUMENT_ROOT'].BASE_DIR.'admin/');
    define('BACKUP_BASEPATH',ADMIN_BASEPATH.'backup/');
    define('ADMIN_TEMPLATES', DIR_TEMPLATES.'admin/');
    define('ADMIN_CONTENT', ADMIN_TEMPLATES.'content/');
    define('ADMIN_TEMPLATE_FILE',ADMIN_TEMPLATES.'main.tpl.php');
    
    define('ADMIN_ASSETS', ADMIN_URL.'assets/');
    define('ADMIN_CSS', ADMIN_ASSETS.'css/');
    define('ADMIN_JS', ADMIN_ASSETS.'js/');
    define('ADMIN_IMAGES', ADMIN_ASSETS.'images/');
    define('ADMIN_PLUGINS', ADMIN_ASSETS.'plugins/');
    
    // local config
	define('DB_SERVER', 'localhost');
	define('DB_SERVER_USERNAME', 'root');
	define('DB_SERVER_PASSWORD', '');
	define('DB_DATABASE', 'bk');
	define('USE_PCONNECT', 'false');
	define('STORE_SESSIONS', 'mysqli');
    
    include(DIR_INCLUDES.'tcpdf/tcpdf.php');
    include(DIR_INCLUDES.'functions.php');
    include(DIR_INCLUDES.'tables.php');
    
    include(DIR_INCLUDES.'mysqli.class.php');
    include(DIR_INCLUDES.'classes.php');
    include(DIR_INCLUDES.'constants.php');
    $mins = new mysqli_class();
    
    if(isset($_SESSION['admin_id'])&&!isset($_COOKIE['exported'])){
        setcookie('exported',date("d-m-Y H:i:s"),strtotime(date("Y-m-d 23:59:59")));
        header("location:".ADMIN_URL."export.php");exit;
    }
     
?>