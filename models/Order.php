<?php

namespace Gorder;

use Illuminate\Database\Eloquent\Model as Model;

Class Entry extends Model {


	//public $connection = 'sqlite';
	
	public static $name = "christmas";

	public $timestamps = false;

	public $protected = [];
}