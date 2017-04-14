<?php

namespace App\Modules\Store\Services;

use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use DB;
use Request;

class StoreService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $name = Request::input('name');

        $length = (int) Request::input('pageSize', 10);

        $departments = Store::with('parent', 'merchant')->likeName($name)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $departments['rows'] = $departments['data'];
        $departments['pages'] = $departments['last_page'];
        unset($departments['per_page'], $departments['current_page'], $departments['last_page'], $departments['next_page_url'], $departments['prev_page_url'], $departments['from'], $departments['to'], $departments['data']);

        return $departments;
    }

    public function save($request)
    {
        $departmentId = $request->input('id', 0);
        $department = Store::firstOrNew(['id' => $departmentId]);
        $user = Auth::user();

        if ($request->examine_status) {
            $department->examine_status = $request->examine_status;
            $department->examine_date = new Carbon();
            $department->examine_by = $user->id;
            $department->examine_remark = $request->examine_remark;
        } else if ($request->activate_status) {
            $department->activate_status = $request->activate_status;
            $department->activate_date = new Carbon();
            $department->activate_by = $user->id;
            $department->activate_remark = $request->activate_remark;
        } else {
            $department->fill($request->all());
        }

        if (!$department->exists) {
            $department->created_by = $user->id;
            $department->created_date = new Carbon();
        }

        return $department->save();
    }

    public function destroy($id = 0)
    {
        DB::beginTransaction();
        $result = '删除失败';

        try {
            $ids = $id ? $id : Request::input('ids', 0);

            Store::destroy($ids);

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
