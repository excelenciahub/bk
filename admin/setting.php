<?php
    require_once("../include/config.php");
    
    $instance = new mysqli_class();
    
    if(count($_POST)>0){
        foreach($_POST as $key=>$val){
            if($instance->db_input($val)==''){
                continue;
            }
            $q = "INSERT INTO `".SITE_CONFIG_LOG."` (`option`, `value`, `comment`, `editable`)  
                    SELECT `option`, `value`, `comment`, `editable`
                      FROM `".SITE_CONFIG."` where `option`='".$instance->db_input($key)."' ";
            
            $instance->db_query($q);
            $q = "update `".SITE_CONFIG."` SET `value`='".$instance->db_input($val)."' where `option`='".$instance->db_input($key)."'";
            $instance->db_query($q);
        }
        $_SESSION['success'] = "Record updated.";
        header("location:".CURRENT_URL);exit;
    }
    $q = "SELECT * FROM `".SITE_CONFIG."` WHERE `editable`='1'";
    $instance->db_query($q);
    $records = $instance->db_fetch_all();
    
    $header_icon = 'fa-cogs text-green';
    $header_title = 'Setting';
    require_once(ADMIN_TEMPLATE_FILE);
?>