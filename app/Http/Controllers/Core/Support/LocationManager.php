<?php

namespace App\Http\Controllers\Core\Support;

use Enhance;
use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Enhance\File\Storage\FileStorage;
use App\Enhance\File\Storage\Exceptions\FileNotUploadedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait LocationManager
{
    use UploadsPhoto;

    protected function uploadLocationPhotoFromRequest(Request $request, $photoRequired = true, $photoInput = null)
    {
        return $this->uploadPhotoFromRequest($request, 'images/locations', $photoRequired, $photoInput);
    }

    protected function createLocationFromRequest(Request $request, $photoRequired = true, $photoInput = null)
    {
        $input = $request->only('name', 'address', 'city', 'state', 'country');
        $photo = $this->uploadLocationPhotoFromRequest($request, $photoRequired, $photoInput);

        return $this->locationWithPhotoStorage($input, $photo);
    }

    protected function createLocationFromRequestInput(Request $request, $input, $photoRequired = true, $photoInput = null)
    {
        $location = $request->input($input);

        if (empty($location) || !is_array($location)) {
            throw new BadRequestHttpException('Bad location data.');
        }

        $location = collect($location)->only('name', 'address', 'city', 'state', 'country');
        $photo = $this->uploadLocationPhotoFromRequest($request, $photoRequired, $photoInput);

        return $this->locationWithPhotoStorage($location->toArray(), $photo);
    }

    protected function locationWithPhotoStorage($location, FileStorage $photoStorage = null)
    {
        if (! $location instanceof Location) {
            $input = is_array($location) ? $location : [];
            $input['photo'] = $photoStorage ? $photoStorage->getStorageUrl() : null;

            $location = Location::forceCreate($input);
        }

        return [
            'location' => $location,
            'photo' => $photoStorage,
        ];
    }
}
