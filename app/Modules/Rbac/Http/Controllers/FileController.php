<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\FileRequest;
use App\Modules\Rbac\Services\FileService;
use Request;

class FileController extends Controller
{

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(FileRequest $fileRequest)
    {
        $file = $this->fileService->upload($fileRequest);
        return response()->json(['success' => (boolean) $file, 'msg' => $file ? '上传成功！' : '上传失败！', 'data' => $file]);
    }

    public function download()
    {
        $path = Request::input('path');
        $basename = basename($path);
        return response()->download(base_path() . $path, $basename);
    }

}
