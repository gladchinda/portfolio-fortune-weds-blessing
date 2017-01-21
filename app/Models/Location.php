<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	public $timestamps = false;

    public function photos()
    {
        return $this->hasMany(LocationPhoto::class, 'location');
    }
}
