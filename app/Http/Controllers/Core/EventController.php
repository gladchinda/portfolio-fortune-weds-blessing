<?php

namespace App\Http\Controllers\Core;

use DB;
use Exception;
use App\Models\Event;
use App\Models\Label;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Support\LocationManager;

class EventController extends Controller
{
	use LocationManager;

	public function getLabels(Request $request)
	{
		return Label::all();
	}

	public function createLabel(Request $request)
	{
		$label = $request->input('label');

    	if (Label::whereLabel($label)->count() > 0) {
    		throw new Exception('Label already exists.');
    	}

		return Label::forceCreate(['label' => $label]);
	}

	public function getServices(Request $request)
	{
		return Service::all();
	}

	public function createService(Request $request)
	{
		$service = $request->input('service');

    	if (Service::whereService($service)->count() > 0) {
    		throw new Exception('Service already exists.');
    	}

		return Service::forceCreate(['service' => $service]);
	}

	public function getAllEvents(Request $request)
	{
		return Event::all();
	}

	public function createEvent(Request $request)
	{
		return DB::transaction(function() use ($request) {

			$input = $request->only('name', 'description', 'location', 'date', 'start', 'end');

			try {
				$location = $this->createLocationFromRequestInput($request, 'location', true, 'location.photo');

				$input['location'] = $location['location']->id;

				return Event::forceCreate($input);

			} catch (Exception $e) {

				if (isset($location)) {
					$this->rollbackPhotoStorage($location, 'photo');
				}

				throw $e;
			}
		});
	}

	public function getEvent(Request $request, Event $event)
	{
		return $event;
	}

	public function modifyEvent(Request $request, Event $event)
	{
		return Event::unguarded(function() use ($request, $event) {

			$input = collect($request->only('name', 'description', 'date', 'start', 'end'));

			$event->update($input->filter(function($value) {
				return !!$value;
			})->toArray());

			return $event;
		});
	}

	public function getEventCrew(Request $request, Event $event)
	{
		return $event->crew;
	}

	public function getEventPeople(Request $request, Event $event)
	{
		return $event->people;
	}

	public function getAllAttending(Request $request, Event $event)
	{
		return $event->attending;
	}
}
