<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class productDetailsController extends Controller
{
    public function index(Request $request){
	// Usage of path method
	$url = $request->input('product_url');
	$content = file_get_contents($url);
	$vowels = array("\t", "\n", "\"", "  ");
	$rpl= array("", "", "'", "");
	//print_r($content);
	if($content){

	//item name
	//echo "ITEM NAME<br>";
	$name_space = explode( '<h1 class="product-name" itemprop="name">' , $content );
	$name_space = explode("</h1>" , $name_space[1] );
	$name_space = str_replace($vowels, $rpl, $name_space[0]);
	//print_r($name_space[0]);

	///specs
	//echo "<br>ITEM SPEC<br>";
	$spec_space = explode( '<div class="ui-box product-property-main">' , $content );
	$spec_space2 = explode("</div>" , $spec_space[1] );
	$spec_space2 = str_replace($vowels, $rpl, $spec_space2[1]);
	//print_r($spec_space2[1]);

	///packaging details

	//print_r($spec_space2[9]);
	$packaging_spec = str_replace($vowels, $rpl, $spec_space2[9]);

	///store name and location
	//echo "<br>STORE NAME<br>";
	$store_space = explode( "<span class=\"shop-name\"><i>Store:</i>" , $spec_space[0] );
	$store_space = explode("</a></span>" , $store_space[1] );
	$store_space = explode(">" , $store_space[0] );
	$store_space = str_replace($vowels, $rpl, $store_space[1]);
	//print_r($store_space[1]);

	$store_location = explode( "<span class=\"store-location\">" , $spec_space[0] );
	$store_location = explode("</span>" , $store_location[1] );
	//print_r($store_location[0]);// = explode(">" , $store_space[0] );
	$store_location = str_replace($vowels, $rpl, $store_location[0]);


	//price
	//echo "<br>ITEM PRICE<br>";
	$price_space = explode( 'itemprop="priceCurrency" content="USD">US $</span>' , $content );
	$price_space = explode("</div>" , $price_space[1] );
	//$price_space=trim($price_space[0]);
	//print_r($price_space[0]);
    $price_space = str_replace($vowels, $rpl, $price_space[0]);

	//desc
	//echo "<br>PRODUCT DESCRIPTION<br>";
	$desc_space = explode( 'window.runParams.descUrl="//' , $content );
	$desc_space = explode("window.runParams.crosslinkUrl" , $desc_space[1] );
	//print_r($desc_space[0]);
	$url2 = $desc_space[0];
	$content2 = file_get_contents('https://'.$url2);
	$desc_space = explode( 'window.productDescription=\'' , $content2 );
	$desc_space = explode("';" , $desc_space[1] );
	$desc_space = explode("<img" , $desc_space[0] );
	$desc_space = str_replace($vowels, $rpl, $desc_space[0]);
	//print_r($desc_space[0]);

	//images url
	//echo "<br>IMAGE URL<br>";
	$image_space = explode( 'window.runParams.imageBigViewURL=' , $content );
	$image_space = explode("window.runParams.mainBigPic" , $image_space[1] );
	//print_r($image_space[0]);
	$image_space = str_replace($vowels, $rpl, $image_space[0]);

	$snip_array= array('name' => $name_space, 'price' => $price_space, 'specifics' => $spec_space2,'description' => $desc_space, 'packaging details' => $spec_space2[9], 'store_name' => $store_space,'store_location' => $store_location, 'image_space' => $image_space);

	}
//print_r($snip_array);
return response()->json(['result' => $snip_array]);
	

	}
}
