<?php 

require 'vendor/autoload.php';
require 'config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

$all_entries = Entry::all();

$number_of_entries = Entry::all()->count();

echo "<Br>entries: ".$number_of_entries;
echo "<pre>";
print_r($all_entries);
print_r($_POST);
echo "</pre>";

exit;
