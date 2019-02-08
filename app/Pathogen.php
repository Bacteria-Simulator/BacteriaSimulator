<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pathogen extends Model
{
	protected $fillable = [
		'path_id', 'pathogen_name', 'desc_link', 'image', 'formula', 'low_temp', 'mid_temp', 'high_temp', 'infectious_dose'
	];
	public $timestamps = false;
	protected $table = 'pathogen';
	protected $primaryKey = 'path_id';
}
