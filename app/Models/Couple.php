<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
	protected $table = 'couple';
	
	public $timestamps = false;

	public function photos()
	{
		return $this->hasMany(CouplePhoto::class, 'who');
	}

	public static function both()
	{
		return static::all();
	}
}
