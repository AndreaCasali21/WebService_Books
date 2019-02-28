<?php

header ("Content-Type_application/json");

include ("function.php");

if(!empty($_GET['codice'])) {
	$name=$_GET['codice'];
	switch ($name) {
		case 1:
			$cont = count_fumetti();
			if (!$cont) deliver_response(404,"book not found", NULL);
			else deliver_response(200,"success", $cont);
		break;

		case 2:
			$array=elenco_scontati();
			if (is_null($array)) deliver_response(404,"book not found", NULL);
			else deliver_response(200,"success", $array);
		break;

		case 3:
			$array = books_in_date_range($_GET['date1'], $_GET['date2']);
			if (is_null($array)) deliver_response(404,"book not found", NULL);
			else deliver_response(200,"success", $array);
		break;

		case 4:
			$array = get($_GET['idcarrello']);
			if (is_null($array)) deliver_response(404,"book not found", NULL);
			else deliver_response(200,"success", $array);
		break;
	}
}
else deliver_response(400,"Invalid request", NULL);

function deliver_response($status, $status_message, $data) {
	header("HTTP/1.1 $status $status_message");

	$response ['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;

	$json_response=json_encode($response);
	echo $json_response;
}
?>
