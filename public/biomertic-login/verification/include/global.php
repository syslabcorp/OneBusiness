<?php
	/*ini_set("display_errors", 0);
	error_reporting(0);*/

	$base_path		= "http://103.54.100.36:4104/weblogin/public/biomertic-login/verification/";
	$db_name		= "weblogindb";
	$db_user		= "weblogindb";
	$db_pass		= "sk)@!ggc1^12";
	$db_host		= "localhost";
	$time_limit_reg = "15";
	$time_limit_ver = "10";

	$conn = mysql_connect($db_host, $db_user, $db_pass);
	if (!$conn) die("Connection for user $db_user refused!");
	mysql_select_db($db_name, $conn) or die("Can not connect to database!");
?>