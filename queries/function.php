<?php
function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}

    function count_fumetti(){
	$count=0;
	$strb = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\books.json');
	$strd = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\departments.json');
	$strbc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\bookcategories.json');
	$books = json_decode($strb, true); 
	$departments=json_decode($strd,true);
	$bookcategories=json_decode($strbc,true);
	 
	 foreach($books['books'] as $book)
	 {
		 $department=$book['department'];
		 if($department=='fumetti')
		 {
		 foreach($bookcategories['bookcategories'] as $bookcategorie)
		 {
			 if($bookcategorie['category']=='Ultimi arrivi' and $book['ID']==$bookcategorie['book'])
				 $count++;
		 }
		 
		 }
	 }
	 return $count;
 }
 ?>