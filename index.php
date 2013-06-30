<?php
session_start();

include ('mysql.php');


// если пользователь не авторизован

if (!isset($_SESSION['id']))
{
	// то проверяем его куки
	// вдруг там есть логин и пароль к нашему скрипту

	if (isset($_COOKIE['login']) && isset($_COOKIE['password']))
	{
		// если же такие имеются
		// то пробуем авторизовать пользователя по этим логину и паролю
		$login = mysql_escape_string($_COOKIE['login']);
		$password = mysql_escape_string($_COOKIE['password']);

		// и по аналогии с авторизацией через форму:

		// делаем запрос к БД
		// и ищем юзера с таким логином и паролем

		$query = "SELECT `id`
					FROM `users`
					WHERE `login`='{$login}' AND `password`='{$password}'
					LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());

		// если такой пользователь нашелся
		if (mysql_num_rows($sql) == 1)
		{
			// то мы ставим об этом метку в сессии (допустим мы будем ставить ID пользователя)

			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['id'];

			// не забываем, что для работы с сессионными данными, у нас в каждом скрипте должно присутствовать session_start();
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
	
	// если нету такой записи с пользователем
	// ну вдруг удалили его пока он лазил по сайту.. =)
	// то надо ему убить ID, установленный в сессии, чтобы он был гостем
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
	//пользователь не залогинен
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Управление вкладками</title>
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
							<span>'.$user.'</span>(<a href="login.php?logout=1 ">Выход</a>)
						</div>
					';
				}
				else{
					print '
						<div class="b-login__login">
							<form action="login.php" method="post">
								<div class="b-login__title">
									Авторизация
								</div>
								<div><input type="text" name="login" placeholder="Имя пользователя"></div>
								<div><input type="password" name="password" placeholder="Пароль"></div>
								<div><input type="submit" value="Вход"><a class="b-login__register" href="register.php">Регистрация</a></div>
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
			Ссылки необходимо вводить в формате http://link.ru
		</div>
		<div class="b-new b-container">
			<div class="b-container__title">
				Новая ссылка
			</div>
			<div class="b-container">
				<form id="newLinkForm">
					<div><input type="text" name="name" placeholder="Название"></div>
					<div><input type="text" name="href" placeholder="Ссылка"></div>
					<div class="u-clearfix"><input type="submit" value="Добавить"></div>
				</form>
			</div>
		</div>
		<div class="b-container b-container b-my">
			<div class="b-container__title">
				Мои ссылки
			</div>
			<div class="b-container__content">
			</div>
		</div>
		<div class="b-shared b-container">
			<div class="b-container__title">
				Ссылки от друзей
			</div>
			<div class="b-container__content">
			</div>
		</div>
		<div class="b-popular b-container">
			<div class="b-container__title">
				Популярные ссылки
			</div>
			<div class="b-container__content">
			</div>
		</div>
	</div>
	<div id="editDialog">
		<form id="editLinkForm">
			<input id="editLinkId" type="hidden" name="link_id" value="">
			<div><input type="text" id="editLinkName" name="name" placeholder="Название"></div>
			<div><input type="text" id="editLinkHref" name="href" placeholder="Ссылка"></div>
			<div class="u-clearfix"><input type="submit" value="Изменить"></div>
		</form>
	</div>
	
	<div id="shareDialog">
		<form id="shareLinkForm">
			<div class="b-error">Такого пользователя не существует</div>
			<input id="shareLinkId" type="hidden" name="link_id" value="">
			<div><input type="text" name="user_name" placeholder="Имя пользователя"></div>
			<div class="u-clearfix"><input type="submit" value="Поделиться"></div>
		</form>
	</div>
	<?
		}
		else {
			echo '<div class="b-content">Для использования сайта необходимо авторизоваться</div>';
		}
	?>
</body>
</html>
