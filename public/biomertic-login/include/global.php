<?php
	/*ini_set("display_errors", 0);
	error_reporting(0);*/

	$base_path		= "http://onebusiness.shacknet.biz/OneBusiness/public/biomertic-login/";
	$application_path		= "http://onebusiness.shacknet.biz/OneBusiness/public/";
	$db_name		= "weblogindb";
	$db_user		= "aditya";
	$db_pass		= "aditya0607";
	$db_host		= "localhost";
	$time_limit_reg = "15";
	$time_limit_ver = "10";

	$conn = mysql_connect($db_host, $db_user, $db_pass);
	if (!$conn) die("Connection for user $db_user refused!");
	mysql_select_db($db_name, $conn) or die("Can not connect to database!");
?>