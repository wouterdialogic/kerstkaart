<?php 

require 'vendor/autoload.php';
require 'config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

echo "<pre>";
print_r($_GET);
print_r($_POST);
echo "</pre>";

//check image content:
if (!empty($_POST['image'])) {
	$data = $_POST["image"];
} else {
	echo "no image uploaded!";
	exit;
}

//check message content:
if (!empty($_POST['message'])) {
	$message = preg_replace('~[^a-z0-9 ]+~i', '', $_POST["message"]);
	echo "<Br>new message: ".$message;
} else {
	$message = '';
	echo "no text typed in, thats ok!";
}

//check times uploaded:
$number_in_database = Entry::where('REMOTE_ADDR', '=', $_SERVER['REMOTE_ADDR'])->count();
if ($number_in_database > 100) {
	echo "fuck this shit";
	exit;
}

//check and prepare image:
if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
    $data = substr($data, strpos($data, ',') + 1);
    $type = strtolower($type[1]); // jpg, png, gif

    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
        throw new \Exception('invalid image type');
    }

    $data = base64_decode($data);

    if ($data === false) {
        throw new \Exception('base64_decode failed');
    }
} else {
    throw new \Exception('did not match data URI with image data');
}

//make a file name and save:
$t = time();
$r = rand(100,999);
$name = "img-$t-$r.{$type}";
$size_of_file = file_put_contents($name, $data);

//save to database:
if ($size_of_file) {
	$entry = new Entry;
	$entry->image_location = $name;
	$entry->REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$entry->HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	$entry->SERVER_JSON = json_encode($_SERVER);
	$entry->text = $message;
	$entry->save();
	//exit;
}

