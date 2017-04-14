<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Region;
use Request;

class RegionService
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

        $region = Region::where('name', 'like', "%{$name}%")->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $region = $region->toArray();
        $region['rows'] = $region['data'];
        $region['pages'] = $region['last_page'];
        unset($region['per_page'], $region['current_page'], $region['last_page'], $region['next_page_url'], $region['prev_page_url'], $region['from'], $region['to'], $region['data']);

        return $region;
    }

    public function find($id)
    {
        return Region::find((int) $id);
    }

    public function findByLevel()
    {
        $level = Request::input('level', 0);
        $pid = Request::input('pid', 0);
        $pname = Request::input('pname', '');
        if (!$pid && $pname) {
            Region::where('name', $pname);
        }
        return Region::where('level', $level)->where('pid', $pid)->get();
    }

    public function save($request)
    {
        $regionId = $request->input('id', 0);
        $region = Region::firstOrNew(['id' => $regionId]);
        $region->fill($request->all());
        if (empty($region->pid)) {
            $region->pid = 0;
            $region->level = 0;
            $region->merge_name = $region->short_name;
        } else {
            $parentRegion = Region::find($region->pid);
            $region->level = $parentRegion->level + 1;
            $region->merge_name = $parentRegion->merge_name . ',' . $region->short_name;
        }

        return $region->save();
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        return Region::destroy($ids);
    }

}
