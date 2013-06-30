<?php
session_start();
include ('../mysql.php');
if (isset($_POST['name']) && isset($_POST['href']) && isset($_SESSION['user_id'])){
	$name=mysql_real_escape_string($_POST['name']);
	$href=mysql_real_escape_string($_POST['href']);
	$user_id=mysql_real_escape_string($_SESSION['user_id']);
	$query = "INSERT
				INTO `links`
				SET
					`name`='{$name}',
					`href`='{$href}',
					`user_id`='{$user_id}'";
					
	$sql = mysql_query($query) or die(mysql_error());
	$query = "SELECT *
				FROM `links`
				WHERE `user_id`='{$user_id}'
				ORDER BY id DESC
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($sql)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}
else{
	
}
?>