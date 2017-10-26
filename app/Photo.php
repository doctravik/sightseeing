<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Photo
{
    /**
     * Name of photo.
     *
     * @var string
     */
    protected $name;

    /**
     * \Intervention\Image\Image
     */
    protected $image;

    /**
     * Create a new instance of Photo.
     *
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->image = Image::make($file);
        $this->name = $file->hashName();
    }

    /**
     * Create a new instance of Photo.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return Photo
     */
    public static function create(UploadedFile $file)
    {
        return new static($file);
    }

    /**
     * Resize image.
     *
     * @param  integer $height
     * @param  interger|null $width
     * @return Photo
     */
    public function resize($height = null, $width = 300)
    {
        $this->image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $this;
    }

    /**
     * Save image to the file.
     *
     * @return string $path
     */
    public function save()
    {
        Storage::put($path = $this->getPath(), $this->image->stream());

        return $path;
    }

    /**
     * Get path to the photo.
     *
     * @return string
     */
    public function getPath()
    {
        return 'images/' . $this->name;
    }
}
