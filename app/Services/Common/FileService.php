<?php

namespace App\Services\Common;

use App\Exceptions\InputException;
use App\Helpers\FileHelper;
use App\Models\Image as Images;
use App\Services\Service;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileService extends Service
{
    /**
     * @var string
     */
    protected $diskName;

    /**
     * @var Filesystem
     */
    protected $storage;

    /**
     * @return Filesystem
     */
    private function storage(): Filesystem
    {
        if (!$this->storage) {
            $this->storage = Storage::disk($this->diskName);
        } //end if

        return $this->storage;
    }

    /**
     * Upload image admin
     *
     * @param UploadedFile $file
     * @param $type
     * @return array
     * @throws InputException
     */
    public function uploadImageAdmin(UploadedFile $file, $type = 'admin')
    {
        $this->diskName = config('upload.disk');

        $fileName = FileHelper::constructFileName($file->getClientOriginalName());

        [$fullPath, $thumbPath] = $this->resizeImage($file, $type, $fileName);

        $imageUrl = $this->storage()->url($fullPath);
        $thumbnailUrl = $this->storage()->url($thumbPath);

        return [
            'url' => $imageUrl,
            'thumb' => $thumbnailUrl,
            'type' => $type,
            'fullPath' => $fullPath,
            'thumbPath' => $thumbPath,
        ];
    }

    /**
     * Upload image
     *
     * @param UploadedFile $file
     * @param $type
     * @return array
     * @throws InputException
     */
    public function uploadImage(UploadedFile $file, $type): array
    {
        $this->diskName = config('upload.disk');

        $fileName = FileHelper::constructFileName($file->getClientOriginalName());

        [$fullPath, $thumbPath] = $this->resizeImage($file, $type, $fileName);

        $image = Images::query()->create([
            'imageable_id' => $this->user->id ?? null,
            'imageable_type' => $this->user ? get_class($this->user) : null,
            'url' => $fullPath,
            'thumb' => $thumbPath,
            'type' => $type,
        ]);

        $imageUrl = $this->storage()->url($image->url);
        $thumbnailUrl = $this->storage()->url($image->thumb);

        return [
            'url' => $imageUrl,
            'thumb' => $thumbnailUrl,
            'type' => $image->type,
            'fullPath' => $fullPath,
            'thumbPath' => $thumbPath,
        ];
    }

    /**
     * Fake image
     *
     * @param $type
     * @return array
     * @throws InputException
     */
    public function fakeImage($type): array
    {
        $this->diskName = config('upload.disk');
        $typeImage = config('upload.image_types' . '.' . $type);
        $imageUrl = 'https://via.placeholder.com/' . $typeImage['full_size'][0] . 'x' . $typeImage['full_size'][0] . '.png';

        $fileName = FileHelper::constructFileName();

        [$fullPath, $thumbPath] = $this->resizeImage($imageUrl, $type, $fileName);

        $image = Images::query()->create([
            'imageable_id' => $this->user->id ?? null,
            'imageable_type' => $this->user ? get_class($this->user) : null,
            'url' => $fullPath,
            'thumb' => $thumbPath,
            'type' => $type,
        ]);

        $imageUrl = $this->storage()->url($image->url);
        $thumbnailUrl = $this->storage()->url($image->thumb);

        return ['url' => $imageUrl, 'thumb' => $thumbnailUrl];
    }

    /**
     * Resize
     *
     * @param $image
     * @param $type
     * @param $fileName
     * @return false[]|string[]
     * @throws InputException
     */
    protected function resizeImage($image, $type, $fileName): array
    {
        $img = Image::make($image);
        $typeImage = config('upload.image_types' . '.' . $type);

        if (!$typeImage) {
            throw new InputException(trans('validation.upload_error_type'));
        }//end if

        $fullPath = FileHelper::pathUrl($fileName, config('upload.path_origin_image'));
        $thumbPath = FileHelper::pathUrl($fileName, config('upload.path_thumbnail'));

        $imageOrigin = $img;
        $imageThumb = clone $img;

        if ($typeImage['crop']) {
            $deltaOld = $typeImage['full_size'][0] / $typeImage['full_size'][1];
            $deltaNew = $img->width() / $img->height();

            if ($deltaOld >= $deltaNew) {
                $width = $img->width();
                $height = $width / $deltaOld;
            } else {
                $height = $img->height();
                $width = $height * $deltaOld;
            }//end if

            $img = $img->crop(intval($width), intval($height));

            $imageOrigin = $img->widen($typeImage['full_size'][0], function ($constraint) {
                $constraint->upsize();
            });

            $imageThumb = clone $img;
            $imageThumb = $imageThumb->widen($typeImage['thumb_size'][0], function ($constraint) {
                $constraint->upsize();
            });
        }//end if

        $encodeType = config('upload.webp_ext');
        $webpQuality = config('upload.webp_quality');

        $imageOrigin = $imageOrigin->encode($encodeType, intval($webpQuality))->stream();
        $imageThumb = $imageThumb->encode($encodeType, intval($webpQuality))->stream();

        $this->storage()->put($fullPath, $imageOrigin->__toString());
        $this->storage()->put($thumbPath, $imageThumb->__toString());

        return [$fullPath, $thumbPath];
    }
}
