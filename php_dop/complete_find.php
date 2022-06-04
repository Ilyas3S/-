<?
include "host.php";

$address = $_GET['term'];

$query = "SELECT address FROM adverts
						WHERE address LIKE '%" . $address . "%'";

//echo $query . "\n";

$result = mysqli_query($link, $query);

for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

$d = "";
foreach ($data as $dat) {
	foreach ($dat as $da) {
		$d = $d . '"'. $da .'",';
	}
}

if ($d != "") {
	$d = '[' . substr($d,0,-1) . ']';
	
}
echo $d;
mysqli_close($link);
?>