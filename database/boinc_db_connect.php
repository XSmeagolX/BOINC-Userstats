<?php
	$dbname = "db_name";
	$dbhost = "localhost";
	$dbuser = "db_user";
	$dbpass = "db_passwd";
	$db_conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	
	$db_conn_status = "";
	$connErrorTitle ="";
	$connErrorDescription = "";
?>
