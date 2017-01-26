<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $dates = ['date'];

	public $timestamps = false;

	public function setNameAttribute($name)
	{
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = Str::slug($name);
	}

	public function getLocationAttribute($location)
	{
		return Location::find($location);
	}

	public function labels()
	{
		return $this->hasMany(EventLabel::class, 'event');
	}

	public function crew()
	{
		return $this->hasMany(EventService::class, 'event');
	}

	public function people()
	{
		return $this->hasMany(EventPerson::class, 'event');
	}

	public function attending()
	{
		return $this->hasMany(Attending::class, 'event');
	}

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('D, j M Y');
    }
}
