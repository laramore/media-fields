<?php
/**
 * Define a social field.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2020
 * @license MIT
 */

namespace Laramore\Fields;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\{
    File as EloquentFile, UploadedFile
};
use Laramore\Contracts\Eloquent\LaramoreModel;

class File extends Char
{
    /**
     * Define allowed mime types.
     *
     * @var array
     */
    protected $mimeTypes;

    /**
     * Define the max size of the file.
     *
     * @var integer
     */
    protected $maxSize;

    /**
     * Disk name.
     *
     * @var string
     */
    protected $disk;

    /**
     * Path value.
     *
     * @var string
     */
    protected $path;

    /**
     * Path value.
     * TODO: Check callback.
     * @var callback
     */
    protected $fileName;


    /**
     * During locking, add observers for saving and deleted.
     *
     * @return void
     */
    protected function locking()
    {
        parent::locking();

        $this->getMeta()->getModelClass()::saving([$this, 'save']);
        $this->getMeta()->getModelClass()::deleted([$this, 'delete']);
    }

    /**
     * On save, save the uploaded file.
     *
     * @param LaramoreModel $model
     * @return LaramoreModel|mixed
     */
    public function save(LaramoreModel $model)
    {
        $file = $this->get($model);

        if ($file instanceof UploadedFile) {
            $path = $this->path;

            if (is_callable($path)) {
                $path = $path($model);
            }

            $fileName = $this->fileName
                ? call_user_func($this->fileName, $model, $file)
                : $file->getFileName();

                $filePath = $this->getStorage()->putFileAs($path, $file, $fileName.'.'.$file->extension());

            $this->set($model, $filePath);
        }

        return $model;
    }

    /**
     * On delete, delete the file.
     *
     * @param LaramoreModel $model
     * @return LaramoreModel|mixed
     */
    public function delete(LaramoreModel $model)
    {
        return $this->getStorage()->delete($this->get($model));
    }

    /**
     * Use right Eloquent file object.
     *
     * @param mixed $value
     * @return mixed
     */
    public function cast($value)
    {
        if (\is_string($value) && $value[0] === DIRECTORY_SEPARATOR) {
            return new UploadedFile($value, \basename($value), null, null, true);
        }

        return $value;
    }

    /**
     * Return an url from the file.
     *
     * @param EloquentFile|mixed $value
     * @return string|mixed
     */
    public function serialize($value, ...$args)
    {
        if (config('filesystems.disks.'.$this->disk.'.visibility') !== 'public') {
            $minutes = config('filesystems.temporary_duration', 30);

            return $this->getStorage()->temporaryUrl(
                $value,
                now()->addMinutes($minutes)
            );
        }

        return $this->getStorage()->url($value);
    }

    /**
     * Return content to download.
     *
     * @return string|mixed
     */
    public function download($model)
    {
        return $this->getStorage()->download($this->get($model));
    }

    /**
     * Get storage.
     *
     * @return mixed
     */
    public function getStorage()
    {
        return Storage::disk($this->disk);
    }
}
