<?php

namespace App\Enhance;

use Request;
use Illuminate\Http\UploadedFile;
use App\Enhance\File\Storage\FileStorage;
use App\Enhance\Routing\RoutesIndexFactory;
use App\Enhance\File\Storage\RequestFileStorage;

class EnhancedServiceFactory
{
	public static function routes($index = null)
	{
		$args = array_flatten(func_get_args());
		$factory = new RoutesIndexFactory;
		
		return empty($args) ? $factory : call_user_func_array([$factory, 'load'], $args);
	}

	public static function storage($file, $disk = null)
	{
		return $file instanceof UploadedFile
				? new FileStorage($file, $disk)
				: new RequestFileStorage(Request::instance(), $file, $disk);
	}

	public static function upload($saveIn, $file, $disk = null, $public = true)
	{
		$storage = static::storage($file, $disk);
		
		$public ? $storage->uploadPublic($saveIn) : $storage->upload($saveIn);

		return $storage->isUploaded() ? $storage->getStorageUrl() : false;
	}
}
