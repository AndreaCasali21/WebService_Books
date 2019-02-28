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
	$strb = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json\books.json');
	$books = json_decode($strb, true);
	$strbc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json\bookcategories.json');
	$bookcategories=json_decode($strbc,true);
	$strc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json\categories.json');
	$categories=json_decode($strc,true);

	$arraybooks=[];
	$x=0;
	foreach($books['books'] as $book)
	{
		$id=$book['ID'];
		foreach($bookcategories['bookcategories'] as $bookcategorie)
		{
			if($id==$bookcategorie['book'])
			{
				foreach($categories['categories'] as $categorie)
				{
					if($bookcategorie['category']==$categorie['type'])
					{
						if($categorie['discount']==50){
							$arraybooks[$book['title']] = $categorie['discount'];
						}else if($categorie['discount']==20){
							$arraybooks[$book['title']] = $categorie['discount'];
						} else if($categorie['discount']==0){
							$arraybooks[$book['title']] = $categorie['discount'];
						}
					}
				}

			}
		}
	}
	asort($arraybooks);
	return $arraybooks;
 }
 ?>
