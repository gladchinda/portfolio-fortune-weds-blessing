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
		return collect([
			'bride' => static::which('bride')->first(),
			'groom' => static::which('groom')->first(),
		]);
	}

	public function scopeWhich($query, $which)
	{
		return in_array($which, ['bride', 'groom']) ? $query->whereWhich($which) : $query;
	}
}
