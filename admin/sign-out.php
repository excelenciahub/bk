<?php
	require_once("../include/config.php");
	session_destroy();
	header("location:".ADMIN_URL."sign-in.php");exit;
?>