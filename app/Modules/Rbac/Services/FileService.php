<?php

namespace App\Modules\Rbac\Services;

class FileService
{

    protected function getDestPath()
    {
        return storage_path() . '/uploads/' . date('Y-m-d') . '/';
    }

    public function upload($request)
    {
        $file = $request->file('file');

        $extention = $file->getClientOriginalExtension();
        $fileName = strtoupper(date('His') . '_' . str_random(10));
        $fileName = $fileName . '.' . $extention;

        try {
            $file->move($this->getDestPath(), $fileName);
            return str_replace([base_path(), '\\'], ['', '/'], $this->getDestPath() . $fileName);
        } catch (\Exception $e) {
            return false;
        }
    }

}
