<?php

namespace App\Http\Controllers\Core;

use DB;
use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\LocationPhoto;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Support\LocationManager;

class LocationController extends Controller
{
	use LocationManager;

	public function getAllLocations(Request $request)
	{
		return Location::all();
	}

	public function createLocation(Request $request)
	{
		try {

			$location = $this->createLocationFromRequest($request, true, 'photo');
			return $location['location'];

		} catch (Exception $e) {

			if (isset($location)) {
				$this->rollbackPhotoStorage($location, 'photo');
			}

			throw $e;
		}
	}

	public function getLocation(Request $request, Location $location)
	{
		return $location;
	}

	public function modifyLocation(Request $request, Location $location)
	{
		return Location::unguarded(function() use ($request, $location) {

			$input = collect($request->only('name', 'address', 'city', 'state', 'country'));

			$location->update($input->filter(function($value) {
				return !!$value;
			})->toArray());

			return $location;
		});
	}

	public function attachEventToLocation(Request $request, Location $location)
	{}

	public function getLocationPhotos(Request $request, Location $location)
	{
		return $location->photos;
	}

	public function uploadLocationPhoto(Request $request, Location $location)
	{
		$photo = $this->uploadLocationPhotoFromRequest($request, true, 'photo');

		try {
			$create = LocationPhoto::forceCreate([
				'location' => $location->id,
				'photo' => $photo ? $photo->getStorageUrl() : null,
			]);

			return $location->fresh();

		} catch (Exception $e) {

			if ($photo && $photo->isUploaded()) {
	            $photo->rollback();
	        }

	        throw $e;
		}
	}
}
