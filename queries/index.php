<?php
// process client request (via URL)
	header ("Content-Type_application/json");
	include ("function.php");
	if(!empty($_GET['name'])){
	
			$name=$_GET['name'];
			switch ($name){
				case 1:
					$cont=count_fumetti();
					if($cont==0)
						deliver_response(404,"book not found", NULL);
					else
					{
						deliver_response(200,"success", $cont);
					}
				
				break;
				case 2:
					$array=[];
					$array=query2();
					if($array!=null)
					{
						deliver_response(404,"errore", NULL);
					}
					else	
						deliver_response(200,"success", $array);
				
					break;
			
	
			
	else
	{
		//throw invalid request
		deliver_response(400,"Invalid request", NULL);
	}
	
	function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}

?>