<?php

namespace App\Modules\Merchant\Services;

use App\Modules\Merchant\Models\Department;
use DB;
use Request;

class DepartmentService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $name = Request::input('name');

        $length = (int) Request::input('pageSize', 10);

        $departments = Department::with('parent', 'merchant')->likeName($name)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $departments['rows'] = $departments['data'];
        $departments['pages'] = $departments['last_page'];
        unset($departments['per_page'], $departments['current_page'], $departments['last_page'], $departments['next_page_url'], $departments['prev_page_url'], $departments['from'], $departments['to'], $departments['data']);

        return $departments;
    }

    public function save($request)
    {
        $departmentId = $request->input('id', 0);
        $department = Department::firstOrNew(['id' => $departmentId]);
        $department->fill($request->all());

        return $department->save();
    }

    public function destroy($id = 0)
    {
        DB::beginTransaction();
        $result = '删除失败';

        try {
            $ids = $id ? $id : Request::input('ids', 0);

            Department::destroy($ids);

            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
            $result = '请先删除子部门再删除父部门！';
        } finally {
            return $result;
        }
    }

}
