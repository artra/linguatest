<?php
session_start();
include ('../mysql.php');

if (isset($_POST['link_id']) && isset($_POST['user_name']) && isset($_SESSION['user_id'])){
	$user_name=mysql_real_escape_string($_POST['user_name']);
	$query = "SELECT `id`
				FROM `users`
				WHERE `login`='{$user_name}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($sql);
	if (mysql_num_rows($sql)==1){
		$link_id = mysql_real_escape_string($_POST['link_id']);
		$user_id=$row['id'];
	
		$query = "SELECT `id`
					FROM `links`
					WHERE `id`='{$link_id}'
					LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($sql);
		if (mysql_num_rows($sql)==1){
			$query = "INSERT
						INTO `shared_links`
						SET
							`link_id`='{$link_id}',
							`user_id`='{$user_id}'";
					
			$sql = mysql_query($query) or die(mysql_error());
		}
		else{
			die('ссылки больше не существует');
		}
	}
	else {
		 header('HTTP/1.1 404 user no found)');
	}

}
else{
	
}
?>