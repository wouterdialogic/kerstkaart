<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

//Capsule::schema()->create('entries', function ($table) {
//    $table->increments('id');
//    $table->string('image_location');	
//    $table->string('text')->nullable();		
//    $table->string('REMOTE_ADDR')->nullable();	
//    $table->string('HTTP_X_FORWARDED_FOR')->nullable();	
//    $table->string('SERVER_JSON')->nullable();	
//    $table->string('date')->nullable();		
//    $table->string('time')->nullable();		
//    //
//});
//
//exit;

//$entry = new Entry;
//$entry->image_location = "wqdqwdwqdg.jpg";
//$entry->REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
//if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//	$entry->HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
//}
//$entry->SERVER_JSON = json_encode($_SERVER);
//$entry->text = "message";
//$entry->save();
//exit;

//$mysql = new Order;
//$mysql->connection = "default";
//$mySqlFirst = $mysql->first();
//
//$sqlite = new Order;
//$sqlite->connection = "sqlite";

echo "<Br>remote address: ";	
echo $_SERVER['REMOTE_ADDR'];

$number_in_database = Entry::where('REMOTE_ADDR', '=', $_SERVER['REMOTE_ADDR'])->count();
if ($number_in_database > 20) {
	echo "fuck this shit";
	exit;
}

//count()->where('REMOTE_ADDR', '=', $_SERVER['REMOTE_ADDR']);
echo "<Br> ip address times: ".$number_in_database;

//echo "<Br>HTTP_X_FORWARDED_FOR: ";
//echo $_SERVER['HTTP_X_FORWARDED_FOR'];

//echo "<pre>";
//print_r($_SERVER);
//echo "</pre>";
//
//$server = json_encode($_SERVER);
//echo "<br>".$server;

$entry = Entry::all();
echo "<pre>";
print_r($entry);
echo "</pre>";
//dd($entry->toArray());
exit;
echo "<br>mysql connection: ".$mysql->connection."<br>";
echo "<br>sqlite connection: ".$sqlite->connection."<br>";
echo "<pre";
// echo "<br>timestamps: ".Order::$timestamps;


//for ($i = 0; $i < 2; $i++) {
//	Gorder\Order::create(['title' => 'post nr'.$i]);
//}


$order = Order::first();
$order = Order::find(1)->get();
$order = Order::where('id', '=', 2)->get();
$order = Order::all();
$order = Order::where('id', '=', "1")->first();
//$order = Order::findOrFail(3);
$orders = Order::where('id', '>', 0)->take(13)->get();
$orders = Order::where('id', '>', 0)->skip(10)->take(10)->get();
$countOrders = Order::where('id', '>', 10)->count();
$countOrders = Order::whereRaw('id > ? ', [10])->count();
$orders = Order::chunk(10, function($orders) {
	foreach ($orders as $order) {
		//echo "<br>2 records for you: <br><br>";
		echo "<Br>$order->title";
	}
});

echo "<br>amount of records with an id bigger than 10: ".$countOrders;
$name = "title";

foreach ($orders as $order) {
	echo "<br>title: ".$order->$name;
}
echo "<br><br>";

foreach ($order->toArray() as $key => $part) {
	echo "<br>key: ".$key." - part: ".$part;
}
echo "<br>title: ".$order["title"];

echo "<pre>";
//dd($order->toArray());
echo "</pre>";

//dd(Order::first()->toArray());