<?php
function query2()
{
	$strb = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\books.json');
	$books = json_decode($strb, true); 
	$strbc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\bookcategories.json');
	$bookcategories=json_decode($strbc,true);
	$strc = file_get_contents('C:\Users\alex.carlone\Desktop\restlibri\WebService_Books\json per progetto\categories.json');
	$categories=json_decode($strc,true);
	
	$arraybooks=[];
	foreach($books['books'] as $book)
	{
		$id=$book['id'];
		foreach($bookcategories['bookcategories'] as $bookcategorie)
		{
			if($id==$bookcategorie['id'])
			{
				foreach($categories['categories'] as $categorie)
				{
					if($bookcategorie['category']==$categorie['type'])
					{
						if($categorie['discount']==50)
							$arraybooks[0]=$book['title'];
					}
				}
						
			}
		}
	}
	foreach($books['books'] as $book)
	{
		$id=$book['id'];
		foreach($bookcategories['bookcategories'] as $bookcategorie)
		{
			if($id==$bookcategorie['id'])
			{
				foreach($categories['categories'] as $categorie)
				{
					if($bookcategorie['category']==$categorie['type'])
					{
						if($categorie['discount']==20)
							$arraybooks[0]=$book['title'];
					}
				}
						
			}
		}
	}
	foreach($books['books'] as $book)
	{
		$id=$book['id'];
		foreach($bookcategories['bookcategories'] as $bookcategorie)
		{
			if($id==$bookcategorie['id'])
			{
				foreach($categories['categories'] as $categorie)
				{
					if($bookcategorie['category']==$categorie['type'])
					{
						if($categorie['discount']==0)
							$arraybooks[0]=$book['title'];
					}
				}
						
			}
		}
	}
	
	return $arraybooks;
		
	?>
