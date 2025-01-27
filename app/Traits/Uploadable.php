<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Traits;

use App\Libraries\StorageWithUrl;
use Illuminate\Http\File;
use League\Flysystem\Local\LocalFilesystemAdapter;

trait Uploadable
{
    private StorageWithUrl $storage;

    /**
     * Returns maximum size of the file in bytes. Defaults to 1 MB.
     */
    public function getMaxFileSize()
    {
        return 1000000;
    }

    /**
     * Returns root path of where the files are to be stored.
     */
    abstract public function getFileRoot();

    public function getFileId()
    {
        return $this->id;
    }

    /**
     * Returns a hash with contents of at least 'hash' and 'ext' if there's
     * image or otherwise null.
     *
     * Assumes attributes 'hash' and 'ext' of the object by default.
     */
    public function getFileProperties()
    {
        if (!present($this->hash) || !present($this->ext)) {
            return;
        }

        return [
            'hash' => $this->hash,
            'ext' => $this->ext,
        ];
    }

    /**
     * Sets file properties. Either a hash of 'hash' and 'ext' or null.
     *
     * Assumes attributes 'hash' and 'ext' of the object by default.
     */
    public function setFileProperties($props)
    {
        $this->hash = $props['hash'] ?? null;
        $this->ext = $props['ext'] ?? null;
    }

    public function storage(): StorageWithUrl
    {
        return $this->storage ??= new StorageWithUrl();
    }

    public function fileDir()
    {
        return $this->getFileRoot().'/'.$this->getFileId();
    }

    public function fileName()
    {
        return $this->getFileProperties()['hash'].'.'.$this->getFileProperties()['ext'];
    }

    public function filePath()
    {
        return $this->fileDir().'/'.$this->fileName();
    }

    public function fileUrl()
    {
        if ($this->getFileProperties() === null) {
            return;
        }

        return $this->storage()->url($this->filePath());
    }

    public function deleteWithFile()
    {
        $this->deleteFile();

        return $this->delete();
    }

    public function deleteFile()
    {
        if ($this->getFileProperties() === null) {
            return;
        }

        $this->setFileProperties(null);

        return $this->storage()->deleteDirectory($this->fileDir());
    }

    public function storeFile($filePath, $fileExtension = '')
    {
        $this->deleteFile();
        $this->setFileProperties([
            'hash' => hash_file('sha256', $filePath),
            'ext' => $fileExtension,
        ]);

        $storage = $this->storage();

        if ($storage->getAdapter() instanceof LocalFilesystemAdapter) {
            $options = [
                'visibility' => 'public',
                'directory_visibility' => 'public',
            ];
        }

        $storage->putFileAs(
            $this->fileDir(),
            new File($filePath),
            $this->fileName(),
            $options ?? [],
        );
    }
}
