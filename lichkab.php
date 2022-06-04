<?
include "php_dop/host.php";
include "php_dop/session.php";
if (isset($_GET['exit']) and $_GET['exit'] == 0 or $_GET['exit'])
	header("Location: index.php");

$query = "SELECT id, login, email, phone, agent, date_reg, avatar
								FROM users 
								WHERE email = '" . $_SESSION['email'] . "'";

$result_user = mysqli_query($link, $query);
for ($data_user = []; $row = mysqli_fetch_assoc($result_user); $data_user[] = $row);	
$id_user = $data_user[0]['id'];

$query = "SELECT id, price, description, image, bron
								FROM adverts
								WHERE id_users = " . $id_user;

$result = mysqli_query($link, $query);
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);	

//print_r($query);

function phone(string $p) {
	$out = $p[0].'-('.$p[1].$p[2].$p[3].')-'.$p[4].$p[5].$p[6].'-'.$p[7].$p[8].'-'.$p[9].$p[10];
	return $out;
}
?>
<html>
	<head>
		<?include 'php_dop/head.html';?>
		<title>Личный кабинет</title>
	</head>

	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		<?include "php_dop/header.html"?>
		
		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Главная</a></li>
				<li class="breadcrumb-item active" aria-current="page">Личный кабинет</li>
			  </ol>
			</nav>
		</div>
		
		<div class="pole1 container-fluid bg-light bg-opacity-75 p-2 col-8 rounded">
			<div class="pole2 container-fluid d-flex rounded p-2">
				<div class="p-2 w-25">
					<div class="ratio ratio-1x1 w-75 mx-auto">
						<img src="images/avatar/<? echo ($data_user[0]['avatar'] ? trim($data_user[0]['avatar']) : 'ava.png'); ?>" class="rounded-circle" style="">
					</div>
				</div>
			
				<div class="d-flex flex-column justify-content-between px-3 fs-5">
					<div class="h4"><?echo $data_user[0]['login']; ?></div>
					<div>Дата регистрации - <?echo $data_user[0]['date_reg']; ?></div>
					<u><? echo ($data_user[0]['agent'] ? 'Агент' : 'Собственник'); ?></u>
				</div>	
			
				<div class="ms-auto">
					<div class="redpr"><a class="btn btn-primary" href="reg.php?redact=1" role="button">Редактировать профиль</a></div>
				</div>	
			</div>
			<div class="rounded">
				<div class="d-flex p-2 mt-1 d-flex flex-column col-8">
					<div class="d-flex p-2 pb-0">
						<div class="h5">Номер телефона</div>
						<div class="ms-auto"><? echo phone($data_user[0]['phone']) ?></div>
					</div>
					<div class="d-flex p-2 pt-1">
						<div class="h5">E-mail</div>
						<div class="ms-auto"><? echo $data_user[0]['email'] ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-8 mx-auto"><div class="h4 p-1 ms-2 mt-3">Ваши объявления</div></div>
		<div class="container-fluid col-10">
		<?
			$img_list = array();
			foreach ($data as $dat) {
				$img_list[] = explode(',',$dat['image']);
			}

			if ($data != []) {
				$out = "";
				$i = 0;
				foreach ($data as $dat) {
					$out = $out .
						"<div class='col-10 p-2 mx-auto mt-3 bg-light bg-opacity-75 rounded'>
							<div class='d-flex'>
								<div class='border rounded border-primary col-3 ratio ratio-16x9 overflow-hidden w-25'>
									<img src='images/". ($dat['image'] != '' ? ("homes/" . $img_list[$i][0]) : 'logo.svg') ."' style='' class=''>
								</div>
								<div class='flex-grow-1 px-2 d-flex flex-column'>
									<div class='h4'>". $dat['price'] ." ₽</div>
									<div class='flex-grow-1'>". $dat['description'] ."</div>
									<div class='d-flex'>
										<a href='konkret.php?id_advert=". $dat['id'] ."'>Перейти</a>
										".($dat['bron'] ? "<div class='ms-3 text-muted'>Забронировано</div>" : '')."
									</div>
								</div>
							</div>
						</div>";
					if ($dat['bron']) {
						$br = mysqli_query($link, "
							SELECT login, avatar, phone, email, date_reg FROM users WHERE id = ".$dat['bron']);
						for ($bronner = []; $row = mysqli_fetch_assoc($br); $bronner[] = $row);
						$out = $out . "
						<div class='col-10 mx-auto'>
							<div class='d-flex rounded-pill bg-dark bg-opacity-25 ms-auto col-10 pe-3'>
								<div class='p-2' style='width:15%'>
									<div class='ratio ratio-1x1'>
										<img src='images/avatar/" . (strlen($bronner[0]['avatar']) > 3 ? trim($bronner[0]['avatar']) : 'ava.png')."'' class='rounded-circle'>
									</div>
								</div>
								<div class='flex-grow-1 d-flex flex-column justify-content-around'>
									<div class='d-flex p-1 pb-0'>
										<div class='h5'>".$bronner[0]['login']."</div>
										<div class='h6 ms-auto me-5'>Забронировал</div>
									</div>
									<div class='d-flex p-1 pb-0'>
										<div class='h6'>Номер телефона</div>
										<div class='mx-auto'>".phone($bronner[0]['phone'])."</div>
									</div>
									<div class='d-flex p-1 pt-0 pb-0'>
										<div class='h6'>E-mail</div>
										<div class='mx-auto'>".$bronner[0]['email']."</div>
									</div>
								</div>
							</div>
						</div>";
					}
					$i++;
				}
				echo $out;
			}
			else
				echo "<p class='fs-3 col-10 mx-auto'>Ничего не найдено</p>";
		?>
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