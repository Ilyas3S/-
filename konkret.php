<?
include "php_dop/host.php";
include "php_dop/session.php";
?>
<html>
	<head>
		<?include 'php_dop/head.html';?>
		<title>Конкретное объявление</title>
	</head>

<?
if (isset($_POST['bron'])) {
	if (!isset($_COOKIE[session_name()]))
		header('Location: avtoriz.php');
	if ($_POST['bron']) {
		mysqli_query($link, "UPDATE adverts SET bron = ".$_POST['bron']." WHERE id = ".$_GET['id_advert']);
	}
}

$result = mysqli_query($link, "SELECT a.id, a.id_users, u.login, u.avatar, u.agent, u.date_reg, u.email, u.phone,
								a.id, a.price, a.description, a.image, a.rooms, a.town, a.address, a.date, a.bron
								FROM adverts AS a
								INNER JOIN users AS u
								ON u.id = a.id_users 
								WHERE a.id = " . $_GET['id_advert']);

for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

$login = $data[0]['login'];


$img_list = array();
foreach ($data as $dat) {
	$img_list[] = explode(',',$dat['image']);
}
if ($img_list == [])
	$img_list[] = 'kvartira.jpg';

//print_r(count($img_list));

function phone(string $p) {
	//$p = '89194067672';
	$out = $p[0].'-('.$p[1].$p[2].$p[3].')-'.$p[4].$p[5].$p[6].'-'.$p[7].$p[8].'-'.$p[9].$p[10];
	return $out;
}
?>
	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		<?include "php_dop/header.html"?>
		
		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Главная</a></li>
				<li class="breadcrumb-item active" aria-current="page">Объявление</li>
			  </ol>
			</nav>
		</div>
		
		<div class="bg-light bg-opacity-75 shadow rounded container-fluid mx-auto p-2">
			<div class="row row-cols-lg-2 row-cols-1">
				<div class="col flex-grow-1 pe-0">
					<div class="slider">
						<div id="images" class="carousel slide mx-auto border border-2" data-bs-ride="carousel">
							<?
							$echo = "<div class='carousel-indicators'>";
							$echo2 = "<div class='carousel-inner'>";

							for ($i = 0; $i < count($img_list[0]); $i++) {
								$echo = $echo . "<button type='button' data-bs-target='#images' data-bs-slide-to='". $i ."' class='". ($i == 0 ? 'active' : '') ."' aria-current='true' aria-label='Slide ". ($i+1) ."'></button>";
								$echo2 = $echo2 . "	<div class='carousel-item ratio ratio-16x9 overflow-hidden ". ($i == 0 ? 'active' : '') ."'>
														<img src='images/homes/". $img_list[0][$i] ."' class=''>
													</div>";
							}
							$echo = $echo . "</div>" . $echo2 . "</div>";
							echo $echo;
							if ($i > 1) {?>
								<button class="carousel-control-prev" type="button" data-bs-target="#images"  data-bs-slide="prev">
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Предыдущий</span>
								</button>
								<button class="carousel-control-next" type="button" data-bs-target="#images"  data-bs-slide="next">
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Следующий</span>
								</button>
							<?}?>
						</div>
					</div>
				</div>
				
				<div class="d-flex flex-column col col-lg-5">
					<div class="">
						<div class="d-flex flex-column bg-white p-3 ">
							<div class="row bg-primary bg-opacity-25 rounded">
								<div class="p-2 w-25">
									<div class="ratio ratio-1x1">
										<img src="images/avatar/<? echo ((strlen($data[0]['avatar']) > 3) ? trim($data[0]['avatar']) : 'ava.png'); ?>" class="rounded-circle" style="">
									</div>
								</div>
								
								<div class="col d-flex flex-column justify-content-around">
									<h5><?echo $login; ?></h5>
									<u><? echo ($data[0]['agent'] ? 'Агент' : 'Собственник'); ?></u>
								</div>
							</div>
							<div class="rounded mt-1">
								<div class="d-flex p-1 d-flex flex-column">
									<div class="d-flex p-1 pb-0">
										<div class="h5">Номер телефона</div>
										<div class="ms-auto"><? echo phone($data[0]['phone']) ?></div>
									</div>
									<hr>
									<div class="d-flex p-1 pt-0 pb-0">
										<div class="h5">E-mail</div>
										<div class="ms-auto"><? echo $data[0]['email'] ?></div>
									</div>
									<hr>
									<div class="d-flex p-1 pt-0">
										<div class="h5">Дата регистрации</div>
										<div class="ms-auto"><? echo $data[0]['date_reg'] ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="bg-white py-2 px-4 d-flex mt-1 h5">
							<div class="">Цена аренды</div>
							<div class="ms-auto"><? echo $data[0]['price'] ?>₽</div>
						</div>
					</div>
					<div class="mt-2 me-2 d-flex">
						<?
						if ($data[0]['id_users'] == $_SESSION['id'] or $_SESSION['agent']) {?>
							<a href='newob.php?redact=<?echo $data[0]['id']?>' class='btn btn-warning'>Редактировать объявление</a>
						<?}?>
						<form method='post' class="d-flex mb-0 ms-auto">
							<input type='hidden' name='bron' value='
								<?
									echo ($data[0]['bron'] == $_SESSION['id'] ? '0' : $_SESSION['id'])
								?>
							'>
							<input type='submit' value='<?echo ($data[0]['bron'] ? ($data[0]['bron'] == $_SESSION['id'] ? 'Разбронировать' : 'Забронировано') : 'Забронировать')?>'id='bron' class='btn py-1 px-4 fs-5
								<?
									echo ($data[0]['bron'] ? ($data[0]['bron'] == $_SESSION['id'] ? 'btn-danger' : '') : 'btn-success')
								?>'<?if($data[0]['bron'] != 0 and $data[0]['bron'] != $_SESSION['id']) echo ' disabled' ?>
							>
						</form>
					</div>
				</div>
			</div>
			<div class="container-fluid row row-cols-2 mt-2">
				<div class="col-12">
					<div class="bg-success bg-opacity-25 p-2">
						<div class="h5">Описание</div>
						<p><?echo $data[0]['description']?></p>
					</div>
					<hr>
				</div>
				<div class="sh3 col">
					<div class="bg-success bg-opacity-25 p-2">
						<div class="h5">Адрес</div>
						<p><?echo $data[0]['address']?></p>
					</div>
					<hr>
				</div>
				<div class="col">
					<div class="bg-success bg-opacity-25 p-2">
						<div class="h5">Город</div>
						<p><?echo $data[0]['town']?></p>
					</div>
					<hr>
				</div>
				<div class="col">
					<div class="bg-success bg-opacity-25 p-2">
						<div class="h5">Дата объявления</div>
						<p><?echo $data[0]['date']?></p>
					</div>
				</div>
				<div class="col">
					<div class="bg-success bg-opacity-25 p-2">
						<div class="h5">Комнат в квартире</div>
						<p>
						<?
							if ($data[0]['rooms'] == 1) {
								echo '1 комната';
							} elseif ($data[0]['rooms'] >= 2 and $data[0]['rooms'] <= 4) {
								echo $data[0]['rooms'] . ' комнаты';
							} else {
								echo '5 комнат';
							}
						?>
						</p>
					</div>
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