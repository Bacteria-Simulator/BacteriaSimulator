<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataCollection extends Model
{
	protected $fillable = [
		'data_id', 'pathogen_name', 'food_name', 'temp', 'time', 'cells', 'infectious_dosage', 'doubling_time', 'growth_rate', 'person_type', 'user_email'
	];
	protected $table = 'datacollection';
	protected $primaryKey = 'data_id';
}
