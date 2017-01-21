<?php

namespace App\Enhance\File\Storage\Exceptions;

use Exception;

class RequestFileProblemException extends Exception
{
    public function __construct($message = null)
    {
        $message = $message ?: 'Problem with request file.';
        parent::__construct($message);
    }
}
