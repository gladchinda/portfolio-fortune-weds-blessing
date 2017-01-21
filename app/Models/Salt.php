<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salt extends Model
{
	protected $primaryKey = 'account';
	
	protected $hidden = ['account', 'salt'];
	
    public $timestamps = false;
}
