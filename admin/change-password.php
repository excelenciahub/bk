<?php
    require_once("../include/config.php");
    $response = json_decode(is_login('admin',false));
    
    $instance = new admin_master();
    $return = array();
    $error = '';
    $action = isset($_GET['action'])?$instance->db_input($_GET['action']):'view';
    $id = isset($_GET['id'])?$instance->db_input($_GET['id'],true):0;
    
    if(isset($_POST['action'])&&$_POST['action']=='save'){
        echo $instance->change_password($_POST);exit;
    }
    
    $header_icon = 'fa-cog text-olive';
    $header_title = 'Change Password';
    require_once(ADMIN_TEMPLATE_FILE);
?>