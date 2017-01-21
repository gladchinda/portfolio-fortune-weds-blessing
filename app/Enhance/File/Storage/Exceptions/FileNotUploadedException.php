<?php

namespace App\Enhance\File\Storage\Exceptions;

use Exception;

class FileNotUploadedException extends Exception
{
    public function __construct($message = null)
    {
        $message = $message ?: 'File not uploaded.';
        parent::__construct($message);
    }
}
