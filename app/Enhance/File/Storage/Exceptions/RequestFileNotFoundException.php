<?php

namespace App\Enhance\File\Storage\Exceptions;

use Exception;

class RequestFileNotFoundException extends Exception
{
    public function __construct($message = null)
    {
        $message = $message ?: 'Request file not found.';
        parent::__construct($message);
    }
}
