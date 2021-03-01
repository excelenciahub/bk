<?php
    require_once("../include/config.php");
    $response = json_decode(is_login('admin',false));
    if($response->status==1){
        header("location:".ADMIN_URL."dashboard.php");exit;
    }
    else{
        header("location:".ADMIN_URL."sign-in.php");exit;
    }
?>