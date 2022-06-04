<?


function read($connect,$q) {	#для считывания данных из базы
	#принимает подключение к базе и запрос
	#возвращает массив строк базы данных
	$data = mysqli_query($connect,$query=$q);
	$data = mysqli_fetch_all($data);
	return $data;
}


function checkDB($connect) {
	#проверка подключения к базе данных
	#принимает подключение к базе
	if (!$connect):
		echo "Не удалось подключиться к базе данных!!!";
	endif;
}


function check_email($data,$arr_data) {
	#проверяет есть ли в массиве указанные данные
	foreach ($arr_data as $i):
		if ($data==$i[0]):
			return true;
		endif;
	endforeach;
	return false;
}


function avatar($path,$key,$a) {	#перемещение аватара в папку
	if (is_uploaded_file($_FILES[$key]['tmp_name'])) :
		if (end(explode('.', $_FILES[$key]["name"])) == "png" || end(explode('.', $_FILES[$key]["name"])) == "jpeg" || end(explode('.', $_FILES[$key]["name"])) == "jpg"):  #проверка ключа в массиве
			if($a==1 )move_uploaded_file($_FILES[$key]["tmp_name"],$path."/".$_FILES[$key]["name"]);
			return true;
		else:
			echo "Файл такого типа недопустим!!!";
			return false;
		endif;
	else :
		return true;
	endif;
}

function interpret($rooms) : int
{
	switch ($rooms):
		case "1":
			return 1;
			break;
		case "2":
			return 2;
			break;
		case "3":
			return 3;
			break;
		case "4":
			return 4;
			break;
		case "5":
			return 5;
			break;
	endswitch;
}
?>