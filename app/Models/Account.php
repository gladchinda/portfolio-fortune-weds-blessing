<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $dates = ['created', 'activated', 'disabled'];

    protected $casts = ['creator' => 'boolean'];

    public $timestamps = false;

    public function getEmailAttribute($email)
    {
    	return Email::find($email);
    }

    public function setFullnameAttribute($fullname)
    {
        $this->attributes['fullname'] = $fullname ? ucwords(Str::lower($fullname)) : null;
    }

    public function secret()
    {
    	return $this->hasOne(Secret::class, 'account');
    }

    public function passwordSalt()
    {
    	return $this->hasOne(Salt::class, 'account');
    }

    public function passwords()
    {
    	return $this->hasMany(Password::class, 'account');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'from');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('D, j M Y H:i:s +0000');
    }
}
