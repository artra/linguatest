<?
session_start();

include ('mysql.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>���������� ���������</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link href="stl.css" rel="stylesheet" type="text/css">
	<script type="text/javacript" src="jquery-2.0.0.min.js"></script>
</head>
<body>
<?php
if (empty($_POST))
{
	?> 	
<div class="b-login b-container b-register">
	<div class="b-login__login">
		<form action="register.php" method="post">
			<div class="b-login__title">
				�����������
			</div>
			<div><input type="text" name="login" placeholder="��� ������������"></div>
			<div><input type="password" name="password" placeholder="������"></div>
			<div><input type="submit" value="�����������"></div>
		</form>
	</div>
</div>
	<?php
}
else
{
	// ����������� ��������� ������ �������� mysql_real_escape_string ����� �������� � ������� ��
	
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
	
	
	// ��������� �� ������� ������ (��������, ����� ������ � ������)
	
	$error = false;
	$errort = '';
	
	
	// ���������, ���� ���� � ������� � ����� �� �������
	$query = "SELECT `id`
				FROM `users`
				WHERE `login`='{$login}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql)==1)
	{
		$error = true;
		$errort .= '������������ � ����� ������� ��� ���������� � ���� ������, ������� ������.<br />';
	}
	
	
	// ���� ������ ���, �� ��������� ����� � �������
	
	if (!$error)
	{
		// ���������� ���� � ������
		
		$hashed_password = md5($password);
		
		$query = "INSERT
					INTO `users`
					SET
						`login`='{$login}',
						`password`='{$hashed_password}'";
		$sql = mysql_query($query) or die(mysql_error());
		
		
		print '<h4>�����������, �� ������� ����������������!</h4><a href="/">�� �������</a>';
	}
	else
	{
		print '<h4>�������� ��������� ������</h4>' . $errort;
	}
}

?>
</body>
</html>