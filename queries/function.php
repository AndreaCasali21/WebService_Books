<?php
    function count_fumetti(){
	 $count=0;
	 $strb = file_get_contents('http://10.13.100.37/Github/books.json');
	$strd = file_get_contents('http://10.13.100.37/Github/departments.json');
	$strbc = file_get_contents('http://10.13.100.37/Github/bookcategories.json');
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
 function elenco_scontati(){
	 $strb = file_get_contents('http://10.13.100.37/Github/books.json');
	 $strbc = file_get_contents('http://10.13.100.37/Github/bookcategories.json');
	 $strc = file_get_contents('http://10.13.100.37/Github/categories.json');
	$books = json_decode($strb, true); 	
	$bookcategories=json_decode($strbc,true);
	$categories=json_decode($strc,true);
    foreach($books['books'] as $book)
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