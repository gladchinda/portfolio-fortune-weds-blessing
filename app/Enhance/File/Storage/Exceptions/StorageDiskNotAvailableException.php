<?php

namespace App\Enhance\File\Storage\Exceptions;

use Exception;

class StorageDiskNotAvailableException extends Exception
{
	protected $disk;

    public function __construct($disk, $message = null)
    {
    	$this->disk = $disk;
        $message = $message ?: sprintf("Storage disk[%s] not available.", $this->disk);
        parent::__construct($message);
    }

    public function getDisk()
    {
    	return $this->disk;
    }
}
