<?php
session_start();

include ('mysql.php');


// ���� ������������ �� �����������

if (!isset($_SESSION['id']))
{
	// �� ��������� ��� ����
	// ����� ��� ���� ����� � ������ � ������ �������

	if (isset($_COOKIE['login']) && isset($_COOKIE['password']))
	{
		// ���� �� ����� �������
		// �� ������� ������������ ������������ �� ���� ������ � ������
		$login = mysql_escape_string($_COOKIE['login']);
		$password = mysql_escape_string($_COOKIE['password']);

		// � �� �������� � ������������ ����� �����:

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

			// �� ��������, ��� ��� ������ � ����������� �������, � ��� � ������ ������� ������ �������������� session_start();
		}
	}
}



if (isset($_SESSION['user_id']))
{
	$query = "SELECT `login`
				FROM `users`
				WHERE `id`='{$_SESSION['user_id']}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	
	// ���� ���� ����� ������ � �������������
	// �� ����� ������� ��� ���� �� ����� �� �����.. =)
	// �� ���� ��� ����� ID, ������������� � ������, ����� �� ��� ������
	if (mysql_num_rows($sql) != 1)
	{
		header('Location: login.php?logout');
		exit;
	}
	
	$row = mysql_fetch_assoc($sql);
	
	$user= $row['login'];
}
else
{
	//������������ �� ���������
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>���������� ���������</title>
	<link href="stl.css" rel="stylesheet" type="text/css">
	<link href="jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery-2.0.0.min.js" charset="koi8-r"></script>
	<script type="text/javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="common.js"></script>
</head>
<body>
	<div class="b-header">
		<div class="b-login b-container">
			<?
				if ($user){
					print '
						<div class="b-login__current">
							<span>'.$user.'</span>(<a href="login.php?logout=1 ">�����</a>)
						</div>
					';
				}
				else{
					print '
						<div class="b-login__login">
							<form action="login.php" method="post">
								<div class="b-login__title">
									�����������
								</div>
								<div><input type="text" name="login" placeholder="��� ������������"></div>
								<div><input type="password" name="password" placeholder="������"></div>
								<div><input type="submit" value="����"><a class="b-login__register" href="register.php">�����������</a></div>
							</form>
						</div>
					';
				}
			?>
		</div>
	</div>
	<?
		if ($user){
			
	?>
	<div class="b-content">
		<div class="b-warning b-container">
			������ ���������� ������� � ������� http://link.ru
		</div>
		<div class="b-new b-container">
			<div class="b-container__title">
				����� ������
			</div>
			<div class="b-container">
				<form id="newLinkForm">
					<div><input type="text" name="name" placeholder="��������"></div>
					<div><input type="text" name="href" placeholder="������"></div>
					<div class="u-clearfix"><input type="submit" value="��������"></div>
				</form>
			</div>
		</div>
		<div class="b-container b-container b-my">
			<div class="b-container__title">
				��� ������
			</div>
			<div class="b-container__content">
			</div>
		</div>
		<div class="b-shared b-container">
			<div class="b-container__title">
				������ �� ������
			</div>
			<div class="b-container__content">
			</div>
		</div>
		<div class="b-popular b-container">
			<div class="b-container__title">
				���������� ������
			</div>
			<div class="b-container__content">
			</div>
		</div>
	</div>
	<div id="editDialog">
		<form id="editLinkForm">
			<input id="editLinkId" type="hidden" name="link_id" value="">
			<div><input type="text" id="editLinkName" name="name" placeholder="��������"></div>
			<div><input type="text" id="editLinkHref" name="href" placeholder="������"></div>
			<div class="u-clearfix"><input type="submit" value="��������"></div>
		</form>
	</div>
	
	<div id="shareDialog">
		<form id="shareLinkForm">
			<div class="b-error">������ ������������ �� ����������</div>
			<input id="shareLinkId" type="hidden" name="link_id" value="">
			<div><input type="text" name="user_name" placeholder="��� ������������"></div>
			<div class="u-clearfix"><input type="submit" value="����������"></div>
		</form>
	</div>
	<?
		}
		else {
			echo '<div class="b-content">��� ������������� ����� ���������� ��������������</div>';
		}
	?>
</body>
</html>
