<?php

namespace App\Http\Controllers\Core;

use DB;
use Storage;
use Exception;
use App\Models\Event;
use App\Models\Label;
use App\Models\Couple;
use App\Models\Person;
use App\Models\Attendee;
use App\Models\CouplePhoto;
use App\Models\EventPerson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Support\UploadsPhoto;

class PeopleController extends Controller
{
	use UploadsPhoto;

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
	{
		return Couple::unguarded(function() use ($request, $couple) {

			$input = collect($request->only('firstname', 'lastname', 'middlename', 'nickname', 'birthday', 'occupation', 'bio', 'community', 'lga', 'state', 'father', 'mother', 'facebook', 'instagram', 'twitter'));

			$files = collect(['photo', 'father_photo', 'mother_photo']);
			$regex = '/(images\/couple\/' . $couple->which . '\/.+)$/';
			$container = $old = [];

			try {
				$files->each(function ($file) use ($request, $couple, $regex, $input, &$container, &$old) {

					$matches = preg_match($regex, $couple->{$file}, $match);
					$old[$file] = $matches ? $match[1] : null;

					$storeIn = 'images/couple/' . $couple->which;
					$photo = $this->uploadPhotoFromRequest($request, $storeIn, !$old[$file], $file);

					$old[$file] = $old[$file] && $photo ? $old[$file] : null;

					$container[$file] = $photo;
					$input[$file] = $photo ? $photo->getStorageUrl() : null;
				});

				$couple->forceFill($input->filter(function($value) {
					return !!$value;
				})->toArray())->save();

				$files->each(function ($file) use ($old) {
					$file = $old[$file];
					if (! empty($file)) {
						Storage::disk('s3')->delete($file);
					}
				});

				return $couple->fresh();

			} catch (Exception $e) {

				$files->each(function ($file) use ($container) {
					$this->rollbackPhotoStorage($container, $file);
				});

				throw $e;
			}
		});
	}

	public function getCouplePhotos(Request $request, Couple $couple)
	{
		return $couple->photos->pluck('photo');
	}

	public function uploadCouplePhoto(Request $request, Couple $couple)
	{
		$storeIn = 'images/couple/' . $couple->which;
		$photo = $this->uploadPhotoFromRequest($request, $storeIn, true, 'photo');

		try {
			$create = CouplePhoto::forceCreate([
				'who' => $couple->id,
				'photo' => $photo ? $photo->getStorageUrl() : null,
			]);

			return $couple->fresh()->photos->pluck('photo');

		} catch (Exception $e) {

			if ($photo && $photo->isUploaded()) {
	            $photo->rollback();
	        }

	        throw $e;
		}
	}

	public function getPeople(Request $request)
	{
		return Person::all();
	}

	public function createPerson(Request $request)
	{
		return Person::unguarded(function() use ($request) {

			$input = collect($request->only('firstname', 'lastname', 'facebook', 'instagram', 'twitter'));

			$photo = $this->uploadPhotoFromRequest($request, 'images/avatars', true, 'avatar');
			$input['avatar'] = $photo ? $photo->getStorageUrl() : null;

			$names = [];
			
			collect(['firstname', 'lastname'])->each(function ($name) use ($input, &$names) {

				$name = $input[$name];

				if (is_string($name) && preg_match('/^[a-z]+$/i', $name)) {
	    			array_push($names, $name);
	    		}
			});

			$input['fullname'] = join(' ', $names);

			try {

				$person = Person::forceCreate($input->filter(function($value) {
					return !!$value;
				})->except('firstname', 'lastname')->toArray());

				return $person->fresh();

			} catch (Exception $e) {

				if ($photo && $photo->isUploaded()) {
					$photo->rollback();
				}

				throw $e;
			}
		});
	}

	public function getPerson(Request $request, Person $person)
	{
		return $person;
	}

	public function modifyPerson(Request $request, Person $person)
	{
		return Person::unguarded(function() use ($request, $person) {

			$input = collect($request->only('firstname', 'lastname', 'facebook', 'instagram', 'twitter'));

			$photo = $this->uploadPhotoFromRequest($request, 'images/avatars', false, 'avatar');

			$regex = '/(images\/avatars\/.+)$/';
			$matches = preg_match($regex, $person->avatar, $match);

			$oldAvatar = $matches && $photo ? $match[1] : null;

			$input['avatar'] = $photo ? $photo->getStorageUrl() : null;

			$names = [];
			
			collect(['firstname', 'lastname'])->each(function ($name) use ($input, &$names) {

				$name = $input[$name];

				if (is_string($name) && preg_match('/^[a-z]+$/i', $name)) {
	    			array_push($names, $name);
	    		}
			});

			$input['fullname'] = join(' ', $names);

			try {

				$person->update($input->filter(function($value) {
					return !!$value;
				})->except('firstname', 'lastname')->toArray());

				if ($oldAvatar) {
					Storage::disk('s3')->delete($oldAvatar);
				}

				return $person->fresh();

			} catch (Exception $e) {

				if ($photo && $photo->isUploaded()) {
					$photo->rollback();
				}

				throw $e;
			}
		});
	}

	public function getPersonLabels(Request $request, Person $person)
	{
		return $person->labels;
	}

	public function createPersonLabel(Request $request, Person $person)
	{
		return DB::transaction(function() use ($request, $person) {

			$input = $request->only('event', 'label');

			$label = $input['label'];
			$event = Event::findOrFail($input['event']);

			if (is_array($label)) {

				$input = collect($label)->only('name', 'many');
				$name = $input['name'];
				$many = $input['many'];

				$many = !is_bool($many) ?: $many;

				if (Label::whereLabel($name)->count() > 0) {
		    		throw new Exception('Label already exists.');
		    	}

				$label = Label::forceCreate(['label' => $name]);

				$eventLabel = EventLabel::forceCreate([
					'event' => $event->id,
					'label' => $label->id,
					'many' => $many,
				]);

			} else {

				$label = is_numeric($label)
						? Label::findOrFail($label)
						: Label::whereLabel($label)->firstOrFail();

				$eventLabel = $event->labels()->whereLabel($label->id)->firstOrFail();
			}

			$canHaveMany = $eventLabel->many;
			$eventPeople = EventPerson::label($label->id)->count();

			if (!$canHaveMany && $eventPeople >= 1) {
				throw new Exception('Cannot attach person to label.');
			}

			$eventPerson = EventPerson::forceCreate([
				'event' => $event->id,
				'label' => $label->id,
				'who' => $person->id,
			]);

			return $person->fresh()->labels;

		});
	}
}
