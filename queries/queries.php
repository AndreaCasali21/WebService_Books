<?php
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
				return $cont;
				break;
				case 2:
			break;
	
			}
	}			
	else
	{
		//throw invalid request
		deliver_response(400,"Invalid request", NULL);
	}
?>