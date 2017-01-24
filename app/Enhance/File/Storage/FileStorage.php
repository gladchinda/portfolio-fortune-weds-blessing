<?php

namespace App\Enhance\File\Storage;

use Carbon;
use Config;
use Storage;
use Illuminate\Http\UploadedFile;
use App\Enhance\File\Storage\Exceptions\FileNotUploadedException;
use App\Enhance\File\Storage\Exceptions\DuplicateFileUploadedException;
use App\Enhance\File\Storage\Exceptions\StorageDiskNotAvailableException;

class FileStorage
{
    protected $file;

    protected $disk;

    protected $path = null;

    protected $url = null;

    protected $uploaded = false;

    public function __construct(UploadedFile $file, $disk = 's3')
    {
        $this->file = $file;
        $this->setStorageDisk($disk);
    }

    public function setStorageDisk($disk = null)
    {
        $disk = is_string($disk) ? $disk : null;

        if (!array_key_exists($disk, Config::get('filesystems.disks'))) {
            throw new StorageDiskNotAvailableException($disk);
        }

        $this->disk = $disk;
    }

    public function fresh($disk = 's3')
    {
        return new static($this->file, $disk ?: $this->disk);
    }

    protected function generateFilenameForStorage($preserveFilename = false)
    {
        $preserveFilename = is_bool($preserveFilename) && $preserveFilename;

        $filename = strchr($this->file->getClientOriginalName(), '.', true);
        $extension = $this->file->guessExtension();
        $timestamp = Carbon::now()->format('U');

        return sprintf("%s_%s.%s", $timestamp, $preserveFilename ? $filename : md5("{$filename}::{$timestamp}"), $extension);
    }
    
    protected function uploadFile($saveIn, $preserveFilename = false, $public = true)
    {
        if ($this->isUploaded()) {
            throw new DuplicateFileUploadedException;
        }

        $disk = $this->getStorageDisk();

        $public = !is_bool($public) ?: $public;
        $saveAs = $this->generateFilenameForStorage($preserveFilename);

        $storeArguments = [$saveIn, $saveAs, $this->disk];
        $storeMethod = sprintf("store%sAs", $public ? 'Publicly' : null);

        $uploaded = call_user_func_array([$this->file, $storeMethod], $storeArguments);

        if ($uploaded) {
            $this->path = "{$saveIn}/{$saveAs}";
            $this->url = $disk->url($this->path);
            $this->uploaded = true;
        }

        return $uploaded;
    }

    public function upload($saveIn, $preserveFilename = false)
    {
        return $this->uploadFile($saveIn, $preserveFilename, false);
    }

    public function uploadPublic($saveIn, $preserveFilename = false)
    {
        return $this->uploadFile($saveIn, $preserveFilename, true);
    }

    public function rollback()
    {
        $this->assertFileIsUploaded();

        $rollback = $this->getStorageDisk()->delete($this->path);

        if ($rollback) {
            $this->path = null;
            $this->url = null;
            $this->uploaded = false;
        }

        return $rollback;
    }

    protected function assertFileIsUploaded()
    {
        if (! $this->isUploaded()) {
            throw new FileNotUploadedException;
        }
    }

    public function isUploaded()
    {
        return !is_null($this->path) && !is_null($this->url) && $this->uploaded;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function getStorageDisk()
    {
        return Storage::disk($this->disk);
    }

    public function getStoragePath()
    {
        $this->assertFileIsUploaded();
        return $this->path;
    }

    public function getStorageUrl()
    {
        $this->assertFileIsUploaded();
        return $this->url;
    }
}
