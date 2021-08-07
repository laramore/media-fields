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
     * File path.
     *
     * @var callback|\Closure
     */
    protected $filePath;

    /**
     * Root path.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * File name.
     *
     * @var callback|\Closure
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
        $file = $model->getAttribute($this->getNative());

        if ($file instanceof UploadedFile) {
            $path = $this->getFilePath($model, $file);
            $name = $this->getFileName($model, $file);
            $file->move($path, $name);

            $model->setAttribute($this->getNative(), new EloquentFile($path.DIRECTORY_SEPARATOR.$name));
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
        $file = $model->getAttribute($this->getNative());

        if ($file instanceof EloquentFile) {
            return Storage::delete($file->getPath());
        }

        return false;
    }

    /**
     * Get root path.
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * Use right Eloquent file object.
     *
     * @param mixed $value
     * @return mixed
     */
    public function cast($value)
    {
        if (\is_string($value)) {
            if ($value[0] === DIRECTORY_SEPARATOR) {
                return new UploadedFile($value, \basename($value), null, null, true);
            }

            return new EloquentFile($this->getRootPath().$value);
        }

        return $value;
    }

    /**
     * Return an url from the file.
     *
     * @param EloquentFile|mixed $value
     * @return string|mixed
     */
    public function serialize($value)
    {
        return Storage::url($value);
    }

    /**
     * Return the file path.
     *
     * @param LaramoreModel $model
     * @param UploadedFile  $file
     * @return string
     */
    public function getFilePath(LaramoreModel $model, UploadedFile $file): string
    {
        if (\is_null($this->filePath)) {
            return $this->getRootPath();
        }

        return $this->filePath($model, $file);
    }

    /**
     * Return the file name.
     *
     * @param LaramoreModel $model
     * @param UploadedFile  $file
     * @return string
     */
    public function getFileName(LaramoreModel $model, UploadedFile $file): string
    {
        if (\is_null($this->fileName)) {
            return $file->getFileName();
        }

        return $this->fileName($model, $file);
    }
}
