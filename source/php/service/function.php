<?php
function count_fumetti() {
	$count=0;
	$strb = file_get_contents('json/books.json');
	$strd = file_get_contents('json/departments.json');
	$strbc = file_get_contents('json/bookcategories.json');
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
	$strb = file_get_contents('json\books.json');
	$books = json_decode($strb, true);
	$strbc = file_get_contents('json\bookcategories.json');
	$bookcategories=json_decode($strbc,true);
	$strc = file_get_contents('json\categories.json');
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

function books_in_date_range(string $firstdate, string $seconddate) {
    $strb = file_get_contents('json\books.json');
    $books = json_decode($strb, true);

    $inrangebooks = array();
    $date1 = strtotime($firstdate);
    $date2 = strtotime($seconddate);

    foreach ($books['books'] as $book) {
        $bookdate = strtotime($book['date']);
        if ($bookdate >= $date1 && $bookdate <= $date2) array_push($inrangebooks, $book['title']);
    }
    return $inrangebooks;
}

function get($cart_id) {
    $ncopies = 0;
    $booktitles = array();
    $cartusers = array();

    $book_id = 0;
    {
        $bookscart = json_decode(file_get_contents('json\bookscart.json'), true);
        foreach ($bookscart['bookscart'] as $cart) {
            if ($cart['cart'] == $cart_id) {
                $ncopies = $cart['ncopies'];
                $book_id = $cart['book'];
                break;
            }
        }
    }

    {
        $books = json_decode(file_get_contents('json\books.json'), true);
        foreach ($books['books'] as $book) {
            if ($book['ID'] == $book_id) {
                array_push($booktitles, $book['title']);
            }
        }
    }

    {
        $carts = json_decode(file_get_contents('json\carts.json'), true);
        foreach ($carts['carts'] as $cart) {
            if ($cart['ID'] == $cart_id) {
                array_push($cartusers, $cart['utent']);
            }
        }
    }

    return array(
        'ncopies' => $ncopies,
        'bookstitle' => $booktitles,
        'cartusers' => $cartusers
    );
}

 ?>
