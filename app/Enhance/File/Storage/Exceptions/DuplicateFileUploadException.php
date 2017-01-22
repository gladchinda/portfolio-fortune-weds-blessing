<?php

namespace App\Enhance\File\Storage\Exceptions;

use Exception;

class DuplicateFileUploadedException extends Exception
{
    public function __construct($message = null)
    {
        $message = $message ?: 'File is already uploaded.';
        parent::__construct($message);
    }
}
