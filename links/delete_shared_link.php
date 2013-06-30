<?php
session_start();
include ('../mysql.php');

if (isset($_POST['link_id']) && isset($_SESSION['user_id'])){

	$link_id=mysql_real_escape_string($_POST['link_id']);
	$user_id=mysql_real_escape_string($_SESSION['user_id']);

	$query = "DELETE FROM `shared_links`
				WHERE
					`user_id`='{$user_id}' AND `link_id`='{$link_id}' 
				";
					
	$sql = mysql_query($query) or die(mysql_error());
}
else{
	
}
?>