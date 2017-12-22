<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__.'/../database.sqlite',
], "default");

//$capsule->addConnection([
//	'driver' => 'sqlite',
//	'database' => __DIR__.'/../database.sqlite',
//], "sqlite");

//$capsule->addConnection([
//    'driver'    => 'mysql',
//    'host'      => 'localhost',
//    'database'  => 'playground',
//    'username'  => 'playground',
//    'password'  => 'playground',
//    'charset'   => 'utf8',
//    'collation' => 'utf8_unicode_ci',
//    'prefix'    => '',
//]);

//$capsule->setCacheManager();
//$capsule->setEventDispatcher();

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "lol";