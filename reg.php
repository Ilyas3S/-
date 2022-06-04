<?
require_once "php_dop/DB_for_users.php";
$connect = mysqli_connect($host="127.0.0.1",$user="root",$password="",$database="arenda");	#подключение к базе
checkDB($connect);
$arr_data=read($connect,"SELECT id, email FROM `users`");	#получение данных из базы

$redact = (isset($_GET['redact']) ? 1 : 0);

if ($redact) {
	include "php_dop/session.php";
	$result = mysqli_query($connect, $query="SELECT * FROM users WHERE id = ". $_SESSION['id']);
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
}
if (isset($_GET['exit']) and $_GET['exit'] == 0 or $_GET['exit'])
	header("Location: index.php");
?>
<html>
	<head>
		<?include 'php_dop/head.html';?>
		<title><?echo ($redact ? 'Редактирование профиля' : 'Регистрация')?></title>
	</head>
	
	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		<?include "php_dop/header.html"?>
		
		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Главная</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?echo ($redact ? 'Редактирование профиля' : 'Регистрация')?></li>
			  </ol>
			</nav>
		</div>
		
		<div class="container-fluid">
			<div class="col-9 bg-light bg-opacity-75 p-2 mx-auto shadow">
				<form class="d-flex flex-column col-8 gap-2 mx-auto mb-0" method="post" enctype="multipart/form-data">
<?
	if (isset($_REQUEST["btn"])):
		$bool_valid = true;

		$email = $_REQUEST["email"];	#введеная почта
		$login = $_REQUEST["login"];	#логин
		$phone = $_REQUEST["phone"];	#номер телефона

		if (!is_numeric(trim($_REQUEST["phone"])) or strlen(trim($_REQUEST["phone"])) != 11 or $login != strip_tags($login) or $email != strip_tags($email))
			$bool_valid = false;

		if ($bool_valid) {
			if (check_email($_REQUEST["email"],$arr_data) or ($redact and $_REQUEST["email"] != $_SESSION['email'])) :
				echo "<p>Такая почта уже есть!!!</p>";
				$_REQUEST["email"]=null;
			else:
				if (strlen(trim($_REQUEST['login'])) >=6 and strlen(trim($_REQUEST['login'])) <= 20) {
					if (avatar('images/avatar/','avatar',0)):
						if (!$redact or $_POST['check']) {
							$av = '';
							if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
								move_uploaded_file($_FILES['avatar']["tmp_name"],"images/avatar/".$_FILES['avatar']["name"]);
								$av = $_FILES["avatar"]["name"];
							} else {
								$av = 'ava.png';
							}
						}
						$password = $_REQUEST["password"];	#пароль
						$secret = $_REQUEST["password_repeat"];	#секретный пароль
						$date_reg = date('d.m.Y');

						$query;
						if (!$redact) {

							$query = "INSERT INTO `users` (`email`, `login`, `phone`, `password`, `avatar`,`agent`,`date_reg`)
									VALUES ('".$email."','".$login."','".$phone."','".password_hash($password,PASSWORD_BCRYPT)."','".$av."'," .($secret=="admin123" ? 1 : 0). ",'".$date_reg."')";
						} else {
							$query = "UPDATE users SET email = '".$email."', login = '".$login."', phone = '".$phone."', password = '".password_hash($password,PASSWORD_BCRYPT)."'".(isset($_POST['check']) ? ", avatar = '".$av."'" : '')." WHERE id = ". $_SESSION['id'];
						}
						$Mresult = mysqli_query($connect,$query);

						if ($Mresult) {
							$result2 = mysqli_query($connect, "SELECT id, agent FROM users WHERE email = '".$email."'");
							for ($data2 = []; $row = mysqli_fetch_assoc($result2); $data2[] = $row);
							if (!$redact) {
								session_start();
								$_SESSION['email'] = $email;
								$_SESSION['login'] = $login;
								$_SESSION['id'] = $data2[0]['id'];
								$_SESSION['agent'] = $data2[0]['agent'];
								header("Location: lichkab.php");
							} else {
								$_SESSION['email'] = $email;
								$_SESSION['login'] = $login;
								header("Location: lichkab.php");
							}
						} else {
							echo "<p>Что-то пошло не так,<br>Этого не должно было произойти<br></p>";
						}
					endif;
				} else
					echo "<p>Поле 'Логин' должно содержать от 6 до 20 символов</p>";
			endif;
		} else
			echo "<p>Проверьте корректность вводимых символов!</p>";
	endif;
?>
					<label class="h5">Почта
						<input name="email" type="email" class="form-control" value="<?if(!$redact) {if (isset($_POST['email'])) echo $_POST['email'];} else {echo $data[0]['email'];}?>" id="exampleInputEmail1" aria-describedby="emailHelp" required>
					</label>
					<label class="h5">Логин
						<input name="login" type="text" class="form-control" value="<?if(!$redact) {if(isset($_POST['login'])) echo $_POST['login'];} else {echo $data[0]['login'];}?>" placeholder='от 6 до 20 символов' required>
					</label>
					<label class="h5">Номер телефона
						<input name="phone" type="text" class="form-control" value="<?if(!$redact) {if(isset($_POST['phone'])) echo $_POST['phone'];} else {echo $data[0]['phone'];}?>" placeholder='88004002020' required>
					</label>
					<label class="h5">Пароль
						<input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
					</label>
					<?if (!$redact) {?>
						<label class="h6">(Для сотрудников)
							<input name="password_repeat" type="password" class="form-control" id="exampleInputPassword1">
						</label>
					<?} else {?>
						<label class="form-check-label px-1" for='check'>Перезаписать аватар?
						<input type="checkbox" class="form-check-input ms-3" name='check[]' id="check" value='1' checked>
						</label>
					<?}?>
					<label class="h5">Загрузите аватар
						<input type="file" class="form-control" name="avatar">
					</label>

					<input value="<?echo (!$redact ? 'Зарегистрироваться' : 'Изменить')?>" type="submit" name='btn' class="btn btn-primary mt-2 mx-auto">
					<hr>
                </form>
                <div class="d-flex flex-column align-items-center fs-5 mb-1">
					<div class="">Есть аккаунт?</div>
					<a href="avtoriz.php">Авторизация</a>
				</div>
			</div>
		</div>
		
		<?include "php_dop/footer.html"?>
	</body>
	<script src="js/bootstrap.bundle.min.js"></script>
</html>
<?
mysqli_close($connect);
?>
<script>
<?include "php_dop/script_address.html"?>
</script>