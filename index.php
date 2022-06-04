<?
include "php_dop/host.php";
include "php_dop/session.php";

$find_address = ((isset($_COOKIE['address']) and $_COOKIE['address']) ? ("WHERE a.address LIKE '%" . $_COOKIE['address'] . "%' ") : '');

$result = mysqli_query($link, $query="SELECT users.login, a.id, a.price, a.description, a.image, a.rooms, a.town, a.address, a.bron
								FROM adverts AS a
								INNER JOIN users
								ON users.id = a.id_users 
								". $find_address ."
								 LIMIT 20");
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

?>
<html>
	<head>
		<?include 'php_dop/head.html';?>
		<title>Главная</title>
	</head>
	
	<body class="container-fluid" style="background-image: url('images/fon.jpg')">
		<?include "php_dop/header.html"?>

		<div class="mt-1 mb-1">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">Главная</li>
			  </ol>
			</nav>
		</div>
		
		<div class="container-fluid mx-auto p-2">
			<div class="pole2 col-10 mx-auto bg-light bg-opacity-75 d-flex flex-column shadow rounded p-2">
				<div class="pole2t1 col row row-cols-2 justify-content-center gap-1">
					<div class="col-5 p-2">
						<div class="h5">Город: </div>
						<select id="town" class="p-1 rounded">
							<option value="0" selected>Не выбрано</option>
							<option>Магнитогорск</option>
							<option>Челябинск</option>
							<option>Екатеринбург</option>
							<option>Верхнеуральск</option>
							<option>Учалы</option>
						</select>
					</div>
					<div class="col-5 p-2">
						<p class="h5">Цена: </p>
						<div class="d-flex p-1">
							<input class="col-5 rounded" type="text" placeholder="Мин" id="price_min" value=''>
							<input class="col-5 rounded" type="text" placeholder="Макс" id="price_max" value=''>
						</div>
					</div>
					<div class="col-5 d-flex flex-column p-2">
						<p class="h5">Кто разместил: </p>

						<div class="d-flex justify-content-between px-1 col-6">
							<label class="form-check-label" for='check1'>Собственник</label>
							<input type="checkbox" class="form-check-input" id="check1" checked>
						</div>
						<div class="d-flex justify-content-between px-1 col-6">
							<label class="form-check-label" for='check2'>Агент</label>
							<input type="checkbox" class="form-check-input" id="check2" checked>
						</div>
					</div>

				
					<div class="col-5 p-2">
						<div class="h5">Комнат в квартире: </div>
						<select id="rooms" class="p-1 rounded">
							<option value="0" selected>Не выбрано</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</div>
				</div>
				<button type="button" class="btn btn-primary col-2 align-self-end" id="filter_button">Найти
				</button>
			</div>
			
			<div class="pole3 col-10 mx-auto bg-light bg-opacity-75 shadow rounded p-2 mt-2">
				<div class="row row-cols-3 justify-content-around ms-auto">
					
					<div class="col p-2">
						<p class="h5">Цена: </p>
						<div class="ms-1 p-1">
							<select id="sort_price" class="p-1 rounded">
							  <option value="1" selected>По убыванию</option>
							  <option value="2">По возрастанию</option>
							</select>
							<input class="form-check-input" type="radio" name="sort_radio" value='1' checked>
						</div>
					</div>
					
					<div class="col p-2">
						<p class="h5">Комнат в квартире: </p>
						
						<div class="ms-1 p-1">
							<select id="sort_rooms" class="p-1 rounded">
							  <option value="1" selected>По возрастанию</option>
							  <option value="2">По убыванию</option>
							</select>
							<input class="form-check-input" type="radio" name="sort_radio" value='2'>
						</div>
					</div>
					
					<div class="col p-2">
						<p class="h5">По дате: </p>
						<div class="ms-1 p-1">
							<select id="sort_time" class="p-1 rounded">
								<option value="1" selected>Сначала новые</option>
								<option value="2">Сначала старые</option>
							</select>
							<input class="form-check-input" type="radio" name="sort_radio" value='3'>
						</div>

					</div>
				</div>
			</div>
			<div class="col-10 mx-auto"><div class="h4 p-1 ms-2 mt-3">Найдено:</div></div>
			<div id="output_data" class="container-fluid">
			<?
				echo_much_ad($data);
			?>
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
$("#filter_button").click(function(e) {
	e.preventDefault();
	var town = $('#town').val();
	var price_min = (!$('#price_min').val() ? -1 : $('#price_min').val());
	var price_max = (!$('#price_max').val() ? -1 : $('#price_max').val());
	var check1 = ($('#check1').is(':checked') ? 1 : 0);
	var check2 = ($('#check2').is(':checked') ? 1 : 0);
	var check = ((check1 && check2) ? 2 : 
				(check1) ? 0 : 
				(check2) ? 1 : -1);
	
	var rooms = $('#rooms').val();

	var sort_radio = $('input[name="sort_radio"]:checked').val();
	
	var sort;
	if (sort_radio == 1) sort = $('#sort_price').val();
	else if (sort_radio == 2) sort = $('#sort_rooms').val();
	else if (sort_radio == 3) sort = $('#sort_time').val();
	
	if (check == -1) $('#output_data').html("<p class='fs-2'>Ничего не найдено</p>");
	else {
		$.ajax({
			url: 'setcookie.php',
			type: 'POST',
			async: false,
			data: {town:town,price_min:price_min,price_max:price_max,
				   check:check,rooms:rooms,sort:sort,sort_radio:sort_radio},
			success: function() {
				$.ajax({
					url: 'php_dop/index_filter.php',
					async: false,
					success: function(data) {
						$('#output_data').html(data);
					}
				})
			}
		})
	}
})
$('#field_find').autocomplete({
	source: 'php_dop/complete_find.php'
})
$('#Find').submit(function(e) {
	e.preventDefault();
	var find = $('#field_find').val();
	$.ajax({
		url: 'php_dop/find_address.php',
		data: {find:find},
		method: 'post',
		async: false,
		success: function(data) {
			$('#output_data').html(data);
		}
	})
})
</script>