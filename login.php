<?php

session_start();

include ('mysql.php');

if (isset($_GET['logout']))
{
	if (isset($_SESSION['user_id']))
		unset($_SESSION['user_id']);
		
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");
	// � ��������� ��� �� �������
	header('Location: index.php');
	exit;
}

if (isset($_SESSION['user_id']))
{
	// ���� ��� ���������, ������������ ��� ������ �� �������� ��������
	
	header('Location: index.php');
	exit;

}



if (!empty($_POST))
{
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
		
		// ������ �������� ��������� ������ ��� ���� � �������� ����, ������� ���� ������� ����:
		$password = md5($_POST['password']);
		// � ����� �������...

		// ������ ������ � ��
		// � ���� ����� � ����� ������� � �������

		$query = "SELECT `id`
					FROM `users`
					WHERE `login`='{$login}' AND `password`='{$password}'
					LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());

		// ���� ����� ������������ �������
		if (mysql_num_rows($sql) == 1)
		{
			// �� �� ������ �� ���� ����� � ������ (�������� �� ����� ������� ID ������������)

			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['id'];
			
			
			// ���� ������������ ����� "��������� ����"
			// �� ������ ��� � ���� ����� � ����� ������
			
			$time = 86400; // ������ ���� �� 24 ����
				setcookie('login', $login, time()+$time, "/");
				setcookie('password', $password, time()+$time, "/");
			
			// � ������������ ��� �� �������� ��������
			header('Location: index.php');
			exit;

			// �� ��������, ��� ��� ������ � ����������� �������, � ��� � ������ ������� ������ �������������� session_start();
		}
		else
		{
			die('������ ����� ������, ��� ������������ ��� ������ ������� �������. �� ������ <a href="register.php">������������������</a> ��� <a href="index.php">����������� ��� ���</a>');
		}
}


?>
