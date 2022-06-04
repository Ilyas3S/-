<?

$link = mysqli_connect('localhost','root','');
if (!mysqli_select_db($link,'arenda')) {
    mysqli_query($link,'CREATE DATABASE arenda');
    mysqli_select_db($link,'arenda');
}
if (!mysqli_query($link,"SELECT * FROM users LIMIT 1")) {
	mysqli_query($link, "CREATE TABLE users (
									id INTEGER AUTO_INCREMENT PRIMARY KEY, 
									login VARCHAR(50), 
									password varchar(255), 
									email varchar(50),
									phone VARCHAR(15), 
									agent INTEGER(1) DEFAULT 0, 
									date_reg VARCHAR(12),
									avatar VARCHAR(255))"
				);
	mysqli_query($link, "INSERT INTO users (login, password, email, phone, agent, date_reg, avatar) VALUES
		('Ilyas', '".password_hash('1234',PASSWORD_BCRYPT)."', 'ilasz2003@mail.ru', '89194067672', 1, '20.09.2021', 'img1.jpg'),
		('Lazania', '".password_hash('1234',PASSWORD_BCRYPT)."', 'debian@gmail.com', '89823337002', 0, '18.05.2022', 'img2.png'),
		('Рататуй', '".password_hash('1234',PASSWORD_BCRYPT)."', 'mega_movi@gmail.com', '83515550720', 0, '04.06.2022', 'img3.png')
		");
}
if (!mysqli_query($link,"SELECT * FROM adverts LIMIT 1")) {
	mysqli_query($link, "CREATE TABLE adverts (
									id INTEGER AUTO_INCREMENT PRIMARY KEY, 
									id_users INTEGER, 
									price INTEGER, 
									description VARCHAR(255), 
									image VARCHAR(512), 
									rooms INTEGER, 
									town VARCHAR(70), 
									address VARCHAR(128), 
									date VARCHAR(12), 
									bron INTEGER DEFAULT 0,
									FOREIGN KEY (id_users) REFERENCES users (id))"
				);
	mysqli_query($link, "INSERT INTO adverts (id_users, price, description, image, rooms, town, address, date) VALUES
		(3,25500, 'Холодильник есть, диван есть, даже телевихзор имеется, а унитаз и вовсе не сломанный.', 'img1.jpg,img2.jpg', 3, 'Магнитогорск', 'пр.Карла-Маркса 7 кв.1', '21.04.2022'),
		(3,57900, 'Почти уютно, и именно потому, что \'почти\' я и съехал.', 'img3.jpg,img4.jpg,img5.jpg', 5, 'Магнитогорск', 'пр. Ленина 26 к.282', '20.05.2022'),
		(2,18700, 'Без соседей по комнате, квартира в вашем распоряжении', 'img6.jpg', 2, 'Верхнеуральск', 'ул. Завенягина 87, кв.47, 7 подъезд, 3 этаж', '16.01.2022'),
		(1,100000, 'Не правда ли красивый вид из окна? За него стоит здесь жить...', 'img7.jpg,img8.jpg', 3, 'Учалы', 'ул. Зеленая 106, кв.2', '04.06.2022')
		");
}

function echo_much_ad(array $data) {
	$img_list = array();
	foreach ($data as $dat) {
		$img_list[] = explode(',',$dat['image']);
	}

	if ($data != []) {
		$out = "";
		$i = 0;
		foreach ($data as $dat) {
			$out = $out . 
				"<div class='col-10 bg-light bg-opacity-75 d-flex p-2 mx-auto mt-2'>
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
				</div>";
				$i++;
			}
			echo $out;
		}
		else
			echo "<p class='fs-3'>Ничего не найдено</p>";
}
?>
