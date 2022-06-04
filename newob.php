<?
if (!isset($_COOKIE[session_name()])) {
	header("Location: avtoriz.php");
	//exit();
}

require_once "php_dop/DB_for_users.php";
include "php_dop/session.php";

$connect = mysqli_connect($host="127.0.0.1",$user="root",$password="",$database="arenda");	#подключение к базе
checkDB($connect);

$redact = (isset($_GET['redact']) ? 1 : 0);

if (isset($_POST['delete']) and $_POST['delete'] == 1) {
	$yesdel = mysqli_query($connect, $query = "DELETE FROM adverts WHERE id = ". $_GET['redact']);
	if ($yesdel) {
		header('Location: index.php');
	} else
		echo 'Что-то пошло не так';
}

if ($redact) {
	include "php_dop/session.php";
	$result = mysqli_query($connect, $query="SELECT * FROM adverts WHERE id = ". $_GET['redact']);
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
}
if (isset($_GET['exit']) and $_GET['exit'] == 0 or $_GET['exit'])
	header("Location: index.php");
	//exit();
?>
<html>

	<head>
		<?include 'php_dop/head.html';?>
		<?if($redact) {?>
			<title>Редактирование объявления</title>
		<?} else {?>
			<title>Добавить объявление</title>
		<?}?>
	</head>
	
	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		
		<?include "php_dop/header.html"?>
		
		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.php">Главная</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?if ($redact) {?> Редактирование объявления <?} else {?> Добавление<?}?></li>
			  </ol>
			</nav>
		</div>

		<div class="container-fluid">
			<div class="col-9 bg-light bg-opacity-75 p-2 mx-auto shadow">
				<div style='color:red'>
<?
if (isset($_REQUEST["btn"])):
	$bool_valid = true;

	$price = $_REQUEST["price"];	#цена
	$description = $_REQUEST["description"];	#описание
	$address = $_REQUEST["address"];    #адрес

	if (!is_numeric($price) or $price != strip_tags($price) or $description != strip_tags($description) or $address != strip_tags($address))
		$bool_valid = false;
	if ($bool_valid) {
		$checking_files = (!$redact or ($redact and isset($_POST['check'])));
		//print_r($_FILES["image"]['name']);
		if (!$checking_files or (count($_FILES["image"]['name']) <= 5 and $_FILES["image"]['name'][0] != '')) {
	    	$bool = true;
	    	$count = 0;
	    	if ($checking_files) {
	        	$arr_img_name = $_FILES["image"]['name'];
	        	$arr_img_tmp = $_FILES["image"]['tmp_name'];
	        	foreach ($arr_img_name as $img) {
		        	if (!end(explode('.', $img)) == "png" and !end(explode('.', $img)) == "jpeg" and !end(explode('.', $img)) == "jpg") {
						echo "<p>Файл такого типа недопустим!!!</p>";
						$bool = false;
					}
					$count++;
				}
			}
	        if ($bool):
	            $arr_img = $_FILES["image"]['name'];	#фото

	            $image = '';

	            if ($checking_files) {
	                for ($i = 0;$i < $count; $i++) {
	                	move_uploaded_file($_FILES['image']["tmp_name"][$i],"images/homes/".$_FILES['image']["name"][$i]);
	                	$image = $image . $_FILES['image']["name"][$i] . ',';
	                }
	                if ($image != '')
	                	$image = substr($image,0,-1);
	                else
	                	echo "<p>Почему-то нет картинок</p>";
	            }

	            $rooms = interpret($_REQUEST["rooms"]);	#кол-во  комнат
	            $town = $_REQUEST["town"];	    #город

	            $date = date('d.m.Y');  #дата добавления

	            $query;
	            if (!$redact) {
					$query = "INSERT INTO `adverts` (`id_users`,`price`, `image`, `rooms`, `town`,`description`,`address`,`date`, `bron`) VALUES (".$_SESSION['id'].",".$price.",'".$image."',".$rooms.",'".$town."','".$description."','".$address."','".$date."',0)";
				} else {
					$query = "UPDATE adverts SET price = ".$price. (isset($_POST['check']) ? ",image = '".$image."'" : '').",rooms = ".$rooms.", town = '".$town."',description = '".$description."',address = '".$address."' WHERE id = " . $_GET['redact'];
				}
				$result = mysqli_query($connect,$query);
				if ($result) {
					header("Location: lichkab.php");
					exit();
				} else
					echo "<p>Что-то пошло не так,<br>Этого не должно было произойти</p>";
	        endif;
	    } else {
	    	echo "<p>Не менее 1 и не более 5 изображений</p>";
	    }
	} else
	    echo "<p>Неверные символы</p>";
endif;
?>
				</div>
				<form class="mx-auto p-2 d-flex flex-column mb-0" method="POST" enctype="multipart/form-data">
					<div class="row row-cols-lg-2 row-cols-1">
						<div class="col d-flex flex-column gap-2">
							<div>
								<label class="h6" for='price'>Введите цену за месяц проживания</label>
								<div class='d-flex col-5'>
									<input name="price" type="text" id='price' class="form-control flex-grow-1" value="<?if(!$redact) {if (isset($_POST['price'])) echo $_POST['price'];} else {echo $data[0]['price'];}?>" required>
									<input value='₽' class='col-2' disabled>
								</div>
							</div>

							<div>
								<label class="h6" for='address'>Введите адрес</label>
								<input name="address" type="text" id='address' class="form-control" value="<?if(!$redact) {if (isset($_POST['address'])) echo $_POST['address'];} else {echo $data[0]['address'];}?>" required>
							</div>
							<div class="d-flex justify-content-between">
								<p class="h6">Комнат в квартире</p>
								<select name="rooms" class='p-1 w-50 rounded'>
									 <option value="1" <?echo (($redact and $data[0]['rooms'] == 1) ? 'selected' : '')?>>1 комната</option>
									 <option value="2" <?echo (($redact and $data[0]['rooms'] == 2) ? 'selected' : '')?>>2 комнаты</option>
									 <option value="3" <?echo (($redact and $data[0]['rooms'] == 3) ? 'selected' : '')?>>3 комнаты</option>
									 <option value="4" <?echo (($redact and $data[0]['rooms'] == 4) ? 'selected' : '')?>>4 комнаты</option>
									 <option value="5" <?echo (($redact and $data[0]['rooms'] == 5) ? 'selected' : '')?>>5 комнат</option>
								</select>
							</div>

							<div class="d-flex justify-content-between">
								<p class="h6">Город</p>
								<select name="town" class='p-1 w-50 rounded'>
								  <option <?echo (($redact and $data[0]['town'] == 'Магнитогорск') ? 'selected' : '')?>>Магнитогорск</option>
								  <option <?echo (($redact and $data[0]['town'] == 'Челябинск') ? 'selected' : '')?>>Челябинск</option>
								  <option <?echo (($redact and $data[0]['town'] == 'Екатеринбург') ? 'selected' : '')?>>Екатеринбург</option>
								  <option <?echo (($redact and $data[0]['town'] == 'Верхнеуральск') ? 'selected' : '')?>>Верхнеуральск</option>
								  <option <?echo (($redact and $data[0]['town'] == 'Учалы') ? 'selected' : '')?>>Учалы</option>
								</select>
							</div>
							<div>
								<label class="h6" for='image'>Загрузите изображения</label>
								<input type="file" class="form-control" name="image[]" id="image" multiple>
							</div>
							<?if ($redact) {?>
								<label class="form-check-label px-1" for='check'>Перезаписать изображения?
									<input type="checkbox" class="form-check-input ms-3" name='check[]' id="check" value='1' checked>
								</label>
							<?}?>
						</div>
						<div class="col mt-3 mt-lg-0">
							<p class="h6">Введите описание</p>
							<textarea name="description" placeholder="Не более 200 символов ..." id="floatingTextarea2" class="form-control" style="height: 200px;" required><?if(!$redact) {if (isset($_POST['description'])) echo $_POST['description'];} else {echo $data[0]['description'];}?>
							</textarea>
						</div>
					</div>
					<div class='d-flex flex-row-reverse mt-2 mt-lg-0'>
						<input type="submit" class="btn btn-primary" name="btn" value="Отправить">
					</div>
				</form>
				<form class='d-flex flex-row-reverse me-2 mt-1' method='post' id='chance'>

					<?if ($redact) {?>
						<button class='btn btn-danger' id='b_delete'>Удалить объявление</button>
					<?}?>
				</form>
	    	</div>
	    </div>
		<?include "php_dop/footer.html"?>
	</body>	<script src="js/bootstrap.bundle.min.js"></script>
</html>
<?
mysqli_close($connect);
?>
<script>
<?include "php_dop/script_address.html"?>;
$('#b_delete').click(function(e) {
	e.preventDefault();
	$('#chance').html("<input type='submit' class='btn btn-danger' value='Удалить объявление'><div class='me-2 mt-1'>Точно?</div><input hidden value='1' name='delete'>");
})
</script>