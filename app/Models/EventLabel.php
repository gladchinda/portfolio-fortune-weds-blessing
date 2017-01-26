<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLabel extends Model
{
	protected $table = 'event_labels';
	
	protected $casts = ['many' => 'boolean'];
	
	public $timestamps = false;
}
