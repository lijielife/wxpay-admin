<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Payment;
use Request;

class PaymentService
{
    public function getList()
    {
        Request::flash();
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');

        $name = Request::input('name');
        $slug = Request::input('slug');

        $page = (int) Request::input('pageNumber', 1);
        $length = (int) Request::input('pageSize', 10);
        $start = ($page - 1) * $length;

        $region = Payment::where('name', 'like', "%{$name}%")->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $region = $region->toArray();
        $region['rows'] = $region['data'];
        $region['pages'] = $region['last_page'];
        unset($region['per_page'], $region['current_page'], $region['last_page'], $region['next_page_url'], $region['prev_page_url'], $region['from'], $region['to'], $region['data']);

        return $region;
    }

}
