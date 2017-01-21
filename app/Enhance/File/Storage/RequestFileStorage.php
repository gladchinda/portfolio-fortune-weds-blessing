<?php

namespace App\Enhance\File\Storage;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Enhance\File\Storage\Exceptions\RequestFileProblemException;
use App\Enhance\File\Storage\Exceptions\RequestFileNotFoundException;

class RequestFileStorage extends FileStorage
{
    protected $request;

    protected $inputField;

    protected static $defaultInputField = 'uploaded_file';

    public function __construct(Request $request, $inputField = null, $disk = 's3')
    {
        $this->request = $request;
        $this->setRequestInputField($inputField);

        parent::__construct($this->getUploadedFile(), $disk);
    }

    public function setRequestInputField($inputField = null)
    {
        $this->inputField = is_string($inputField) ? $inputField : static::$defaultInputField;
    }

    public function fresh($inputField = null, $disk = 's3')
    {
        $disk = $disk ?: $this->disk;
        $inputField = $inputField ?: $this->inputField;

        return new static($this->request, $inputField, $disk);
    }

    public static function setDefaultInputField($inputField = null)
    {
        static::$defaultInputField = is_string($inputField) ? $inputField : static::$defaultInputField;
    }

    public static function getDefaultInputField()
    {
        return static::$defaultInputField;
    }

    protected function getUploadedFile()
    {
        if (! $this->request->hasFile($this->inputField)) {
            throw new RequestFileNotFoundException;
        }

        $file = $this->request->file($this->inputField);

        if (! ($file && $file->isValid() && $file instanceof UploadedFile)) {
            throw new RequestFileProblemException;
        }

        return $file;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getInputField()
    {
        return $this->inputField;
    }
}
