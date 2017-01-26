<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPerson extends Model
{
	protected $table = 'event_people';
	
	public $timestamps = false;

	public function scopeLabel($query, $label)
	{
		return $query->whereLabel($label);
	}
}
