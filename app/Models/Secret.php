<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
	protected $primaryKey = 'account';
	
	protected $hidden = ['account', 'hash'];

    public $timestamps = false;
}
