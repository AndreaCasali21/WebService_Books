<?php
function count_fumetti(){
	$count=0;
	/* $books=array(
	 "java"=>299,
	 "c"=>348,
	 "php"=>267
	 );*/
	$strb = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\books.json');
	$strd = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\departments.json');
	$strbc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\bookcategories.json');
	$books = json_decode($strb, true); 
	$departments=json_decode($strd,true);
	$bookcategories=json_decode($strbc,true);
	// echo '<pre>' . print_r($books, true) . '</pre>';
	/* foreach($books as $book=>$price)
	 {
		 if($book==$find)
		 {
			 return $price;
			 break;
		 }
	 }*/
	 
	 foreach($books['book'] as $book)
	 {
		 $department=$book['department'];
		 if($department=='fumetti')
		 {
			 foreach(
		 }
	 }
 }

?>