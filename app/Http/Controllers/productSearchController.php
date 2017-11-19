<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class productSearchController extends Controller
{
public function index(Request $request){
	// Usage of path method
	$searchString = $request->input('searchString');
	$searchString= explode(" ", $searchString);
	$searchString=implode("+", $searchString);

	$vowels = array("\t", "\n", "\"", "  ");
	$rpl= array("", "", "'", "");
    $result_array= array();
	$url = 'https://www.aliexpress.com/wholesale?site=glo&g=y&SearchText='.$searchString.'&page=1&initiative_id=AS_20170618062204&needQuery=n&isFreeShip=y&user_click=y';
	$conten = file_get_contents($url);
	//print_r($content);
	$result_space = explode( '<a  class="history-item product " href="//' , $conten );

	//
	//print_r($result_space);
	$iterator=1;
	//echo count($result_space);
	while($iterator < count($result_space)){
	$url_space = explode('" title="' , $result_space[$iterator] );
	$name_space5= $url_space[1];
	$url_space = str_replace($vowels, $rpl, $url_space[0]);

	//price
	//echo "ITEM PRICE<br>";
	$price_space = explode( '<span class="value" itemprop="price">US ' , $result_space[$iterator] );
	$price_space = explode('<strong class="free-s">Free Shipping</strong>' , $price_space[1] );
	$price_space = str_replace($vowels, $rpl, $price_space[0]);

	//item name
	//echo "ITEM NAME<br>";
	$name_space5 = explode( '" itemprop="name" target="_blank">' , $name_space5);
	$name_space5 = explode('" target="_blank">' , $name_space5[0] );
	$name_space5 = str_replace($vowels, $rpl, $name_space5[0]);


	
	//images url
	//echo "IMAGE URL<br>";
	$image_space = explode( 'class="picCore' , $result_space[$iterator-1] );
	$image_space = explode( 'src="' , $image_space[1] );
	$image_space = explode('"' , $image_space[1] );
	$image_space = str_replace($vowels, $rpl, $image_space[0]);
	//print_r($image_space[0]);
	//echo "<br><br>";

	$iterator++;

	$snip_array= array('name'=> $name_space5, 'image_url' => $image_space, 'price' => $price_space, 'details_url' => $url_space);
	array_push($result_array, $snip_array);
	}



	return response()->json(['result' => $result_array]);
	}
}
