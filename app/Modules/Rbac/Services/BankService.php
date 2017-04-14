<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Bank;
use Request;

class BankService
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

        $banks = Bank::where('name', 'like', "%{$name}%")->where('slug', 'like', "%{$slug}%")->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $banks = $banks->toArray();
        $banks['rows'] = $banks['data'];
        $banks['pages'] = $banks['last_page'];
        unset($banks['per_page'], $banks['current_page'], $banks['last_page'], $banks['next_page_url'], $banks['prev_page_url'], $banks['from'], $banks['to'], $banks['data']);

        return $banks;
    }

    public function find($id)
    {
        return Bank::find((int) $id);
    }

    public function save($request)
    {
        $bankId = $request->input('id', 0);
        $bank = Bank::firstOrNew(['id' => $bankId]);
        $bank->fill($request->all());

        return $bank->save();
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        return Bank::destroy($ids);
    }

}
