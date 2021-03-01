<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
	if(isset($error) && $error!=''){
		error_msg($error);
	}
	else if(isset($_SESSION['success'])){
		success_msg($_SESSION['success']);
		unset($_SESSION['success']);
	}
	else if(isset($_SESSION['error'])){
		error_msg($_SESSION['error']);
		unset($_SESSION['error']);
	}
	else if(isset($_SESSION['warning'])){
		warning_msg($_SESSION['warning']);
		unset($_SESSION['warning']);
	}
	else if(isset($_SESSION['info'])){
		info_msg($_SESSION['info']);
		unset($_SESSION['info']);
	}
?>