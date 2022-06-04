<?
include "host.php";

$address = $_POST['find'];

$query = "SELECT id, price, description, image
						FROM adverts
						WHERE address LIKE '%" . $address . "%'";

//echo $query . "\n";

$result = mysqli_query($link, $query);
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

echo_much_ad($data);
mysqli_close($link);
?>