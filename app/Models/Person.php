<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	protected $table = 'people';
	
	public $timestamps = false;

	public function labels()
	{
		return $this->hasMany(EventPerson::class, 'who');
	}
}
