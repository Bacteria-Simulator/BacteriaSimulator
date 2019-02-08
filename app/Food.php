<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
	protected $fillable = [
		'food_id', 'food_name', 'cooked', 'available_water', 'ph_level', 'image_link'
	];
	public $timestamps = false;
	protected $table = 'food';
	protected $primaryKey = 'food_id';
}
