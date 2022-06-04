<?
if (isset($_POST['town'])) setcookie('town', $_POST['town'], time() + 3600);
if (isset($_POST['price_min'])) setcookie('price_min', $_POST['price_min'], time() + 3600);
if (isset($_POST['price_max'])) setcookie('price_max', $_POST['price_max'], time() + 3600);
if (isset($_POST['check'])) setcookie('check', $_POST['check'], time() + 3600);
if (isset($_POST['rooms'])) setcookie('rooms', $_POST['rooms'], time() + 3600);
if (isset($_POST['sort'])) setcookie('sort', $_POST['sort'], time() + 3600);
if (isset($_POST['sort_radio'])) setcookie('sort_radio', $_POST['sort_radio'], time() + 3600);
//if (isset($_POST['sort_time'])) setcookie('sort_time', $_POST['sort_time'], time() + 3600);

if (isset($_POST['find'])) setcookie('address', $_POST['find'], time() + 11);

//if (isset($_POST[''])) setcookie('', $_POST[''], time() + 3600);
?>