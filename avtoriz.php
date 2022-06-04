<?
include "php_dop/host.php";
?>
<html>
	<head>
		<?include 'php_dop/head.html';?>
		<title>Авторизация</title>
	</head>
	
	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		<?include "php_dop/header.html"?>

		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Главная</a></li>
				<li class="breadcrumb-item active" aria-current="page">Авторизация</li>
			  </ol>
			</nav>
		</div>
		
		<div class="pole1 container-fluid">
			<div class="col-9 bg-light bg-opacity-75 p-2 mx-auto shadow">
				<form action="avtoriz.php" class="d-flex flex-column col-8 gap-2 mx-auto mb-0" method="post" enctype="multipart/form-data">
					<?
						if (isset($_POST["email"]) and isset($_POST["pass"])) {
							$query = "SELECT id, login, email, password, agent FROM users
														   WHERE email = '" . $_POST["email"] ."'";
							$result = mysqli_query($link, $query);

							for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

							if ($data != []) {
								if (password_verify($_POST["pass"],$data[0]['password'])) {
									session_start();
									$_SESSION['email'] = $data[0]['email'];
									$_SESSION['login'] = $data[0]['login'];
									$_SESSION['id'] = $data[0]['id'];
									$_SESSION['agent'] = $data[0]['agent'];
									header("Location: index.php");
								} else {
									echo "<p class='h5'>Пароль введен неверно,<br>Попробуйте еще раз<br></p>";
								}
							} else {
								echo "<p class='h5'>Почта введена неверно,<br>Попробуйте еще раз<br></p>";
							}
							//unset($_POST['email']);
							//unset($_POST['pass']);
							//header('Location: avtoriz.php');
							//exit;
						}
					?>
					<label class="form-label h5">Почта
						<input name="email" type="email" class="form-control mb-3" aria-describedby="emailHelp" value="<?if(isset($_POST['email'])) echo $_POST['email'];?>">
					</label>

					<label class="form-label h5">Пароль
						<input name="pass" type="password" class="form-control mb-3">
					</label>

					<input type="submit" class="btn btn-primary mx-auto col-4" value="Войти">
					<hr>
				</form>
				<div class="d-flex flex-column align-items-center fs-5 mb-1">
					<div class="">Нет аккаунта?</div>
					<a href="reg.php">Регистрация</a>
				</div>
			</div>
		</div>

		<?include "php_dop/footer.html"?>
	</body>
	<script src="js/bootstrap.bundle.min.js"></script>
</html>
<?
mysqli_close($link);
?>
<script>
<?include "php_dop/script_address.html"?>
</script>