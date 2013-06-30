<?php
session_start();
include ('../mysql.php');

if (isset($_SESSION['user_id'])){
	$user_id=mysql_real_escape_string($_SESSION['user_id']);
	
	$query = "SELECT *
				FROM `links`
				WHERE `user_id`='{$user_id}'
				LIMIT 10";
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