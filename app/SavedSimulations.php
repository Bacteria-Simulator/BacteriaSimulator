<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedSimulations extends Model
{
	protected $fillable = [
		'saved_sim_id', 'pathogen_name', 'food_name', 'temp', 'time', 'cells', 'simulation_name', 'infectious_dosage', 'doubling_time', 'img', 'growth_rate', 'user_id'
	];
	protected $table = 'savedsimulations';
	protected $primaryKey = 'saved_sim_id';
}
