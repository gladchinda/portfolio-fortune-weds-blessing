<?php

namespace App\Http\Controllers\Core\Support;

use Enhance;
use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Enhance\File\Storage\FileStorage;
use App\Enhance\File\Storage\Exceptions\FileNotUploadedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Enhance\File\Storage\Exceptions\StorageDiskNotAvailableException;

trait LocationManager
{
    protected function uploadLocationPhotoFromRequest(Request $request, $photoRequired = true, $photoInput = null)
    {
        $photoInput = is_string($photoInput) ? $photoInput : ( is_string($photoRequired) ? $photoRequired : 'photo' );

        $photoRequired = !is_bool($photoRequired) ?: $photoRequired;

        try {
            $photoStorage = Enhance::storage($photoInput, 's3');
        } catch (Exception $e) {
            if ($e instanceof StorageDiskNotAvailableException || $photoRequired) {
                throw $e;
            }
        }

        if (isset($photoStorage) && $photoStorage instanceof FileStorage) {
            $uploaded = $photoStorage->upload('images/locations');

            if (! $photoStorage->isUploaded()) {
                throw new FileNotUploadedException('Could not store location photo.');
            }

            return $photoStorage;
        }
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

    protected function rollbackPhotoStorage(array $container, $photoKey = 'photo')
    {
        $photoStorage = $container[$photoKey];

        if ($photoStorage && $photoStorage->isUploaded()) {
            $photoStorage->rollback();
        }
    }
}
