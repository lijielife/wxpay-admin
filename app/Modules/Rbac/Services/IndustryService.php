<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Industry;
use Request;

class IndustryService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');

        $name = Request::input('name');
        $slug = Request::input('slug');

        $page = (int) Request::input('pageNumber', 1);
        $length = (int) Request::input('pageSize', 10);
        $start = ($page - 1) * $length;

        $banks = Industry::with('parent')->where('name', 'like', "%{$name}%")->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $banks = $banks->toArray();
        $banks['rows'] = $banks['data'];
        $banks['pages'] = $banks['last_page'];
        unset($banks['per_page'], $banks['current_page'], $banks['last_page'], $banks['next_page_url'], $banks['prev_page_url'], $banks['from'], $banks['to'], $banks['data']);

        return $banks;
    }

    public function find($id)
    {
        return Industry::find((int) $id);
    }

    public function save($request)
    {
        $industryId = $request->input('id', 0);
        $industry = Industry::firstOrNew(['id' => $industryId]);
        $industry->fill($request->all());
        $industry->pid = $industry->pid ? $industry->pid : null;

        return $industry->save();
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        return Industry::destroy($ids);
    }

}
