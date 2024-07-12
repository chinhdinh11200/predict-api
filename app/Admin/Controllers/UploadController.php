<?php

namespace App\Admin\Controllers;

use App\Models\LuckyWheel;
use App\Services\Common\FileService;
use Dcat\Admin\Traits\HasUploadedFile;

class UploadController
{
    use HasUploadedFile;

    public function upload()
    {
        $disk = $this->disk('local');
        if ($this->isDeleteRequest()) {
            return $this->deleteFileAndResponse($disk);
        }
        $file = $this->file();
        $imageUpload = FileService::getInstance()->uploadImageAdmin($file);
        $path = $imageUpload['fullPath'];

        return $imageUpload
            ? $this->responseUploaded($path, $disk->url($path))
            : $this->responseErrorMessage('文件上传失败');
    }
}
