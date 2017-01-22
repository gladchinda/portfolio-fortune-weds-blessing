<?php

namespace App\Http\Controllers\Core;

use App\Models\Couple;
use App\Models\Person;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeopleController extends Controller
{
	public function getAllAttendees(Request $request)
	{
		return Attendee::all();
	}

	public function createNewAttendee(Request $request)
	{}

	public function getAttendee(Request $request, Attendee $attendee)
	{
		return $attendee;
	}

	public function attendEvent(Request $request, Attendee $attendee)
	{}

	public function getBothCouple(Request $request)
	{
		return Couple::both();
	}

	public function getOneCouple(Request $request, Couple $couple)
	{
		return $couple;
	}

	public function modifyOneCouple(Request $request, Couple $couple)
	{}

	public function getCouplePhotos(Request $request, Couple $couple)
	{
		return $couple->photos;
	}

	public function uploadCouplePhoto(Request $request, Couple $couple)
	{}

	public function getPeople(Request $request)
	{
		return Person::all();
	}

	public function getPerson(Request $request, Person $person)
	{
		return $person;
	}

	public function modifyPerson(Request $request, Person $person)
	{}

	public function getPersonLabels(Request $request, Person $person)
	{
		return $person->labels;
	}

	public function createPersonLabel(Request $request, Person $person)
	{}
}
