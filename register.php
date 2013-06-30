<?
session_start();

include ('mysql.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Управление вкладками</title>
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
				Регистрация
			</div>
			<div><input type="text" name="login" placeholder="Имя пользователя"></div>
			<div><input type="password" name="password" placeholder="Пароль"></div>
			<div><input type="submit" value="Регистрация"></div>
		</form>
	</div>
</div>
	<?php
}
else
{
	// обрабатывае пришедшие данные функцией mysql_real_escape_string перед вставкой в таблицу БД
	
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
	
	
	// проверяем на наличие ошибок (например, длина логина и пароля)
	
	$error = false;
	$errort = '';
	
	
	// проверяем, если юзер в таблице с таким же логином
	$query = "SELECT `id`
				FROM `users`
				WHERE `login`='{$login}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql)==1)
	{
		$error = true;
		$errort .= 'Пользователь с таким логином уже существует в базе данных, введите другой.<br />';
	}
	
	
	// если ошибок нет, то добавляем юзаре в таблицу
	
	if (!$error)
	{
		// генерируем соль и пароль
		
		$hashed_password = md5($password);
		
		$query = "INSERT
					INTO `users`
					SET
						`login`='{$login}',
						`password`='{$hashed_password}'";
		$sql = mysql_query($query) or die(mysql_error());
		
		
		print '<h4>Поздравляем, Вы успешно зарегистрированы!</h4><a href="/">На главную</a>';
	}
	else
	{
		print '<h4>Возникли следующие ошибки</h4>' . $errort;
	}
}

?>
</body>
</html>