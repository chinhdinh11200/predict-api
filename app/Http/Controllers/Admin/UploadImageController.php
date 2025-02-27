<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InputException;
use App\Http\Requests\Admin\UploadImageRequest;
use App\Services\Common\FileService;
use Illuminate\Http\JsonResponse;

class UploadImageController extends BaseController
{
    /**
     * UploadController constructor.
     */
    public function __construct()
    {
        $this->middleware($this->authMiddleware());
    }

    /**
     * Upload
     *
     * @param UploadImageRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function upload(UploadImageRequest $request): JsonResponse
    {
        $data = FileService::getInstance()->uploadImage($request->file('image'), $request->get('type'));

        return $this->sendSuccessResponse($data);
    }
}
