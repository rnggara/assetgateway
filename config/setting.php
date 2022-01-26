<?php 
	include '../adodb5/adodb.inc.php'; 
	include '../adodb5/adodb-pager.inc.php'; 

	$db_host = "localhost"; 
	$db_user = "root"; 
	$db_pass = ""; 
	$db_db = "ejs_1"; 
	$database_type = "mysqli"; 

	$db = NewADOConnection("$database_type"); 
	$db->Connect("$db_host", "$db_user", "$db_pass", "$db_db"); 
	
?>