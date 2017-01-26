<?php

namespace App\Http\Controllers\Core\Support;

use Enhance;
use Exception;
use Illuminate\Http\Request;
use App\Enhance\File\Storage\FileStorage;
use App\Enhance\File\Storage\Exceptions\FileNotUploadedException;
use App\Enhance\File\Storage\Exceptions\StorageDiskNotAvailableException;

trait UploadsPhoto
{
    protected function uploadPhotoFromRequest(Request $request, $storageLocation, $photoRequired = true, $photoInput = null)
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
            $uploaded = $photoStorage->upload($storageLocation);

            if (! $photoStorage->isUploaded()) {
                throw new FileNotUploadedException('Could not store photo.');
            }

            return $photoStorage;
        }
    }

    protected function rollbackPhotoStorage(array $container, $photoKey = 'photo')
    {
        $photoStorage = $container[$photoKey];

        if ($photoStorage && $photoStorage->isUploaded()) {
            $photoStorage->rollback();
        }
    }
}
