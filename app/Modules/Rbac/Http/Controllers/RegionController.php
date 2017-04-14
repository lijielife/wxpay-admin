<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\RegionRequest;
use App\Modules\Rbac\Models\Region;
use App\Modules\Rbac\Services\RegionService;
use Request;

class RegionController extends Controller
{

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function index()
    {
        return view('rbac::region.list');
    }

    public function add()
    {
        return view('rbac::region.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        return view('rbac::region.edit', ['region' => Region::with('parent')->find($id)]);
    }

    public function save(RegionRequest $regionRequest)
    {
        $result = $this->regionService->save($regionRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->regionService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function datagrid()
    {
        $result = $this->regionService->getList();
        return response()->json($result);
    }

    public function findByLevel()
    {
        $result = $this->regionService->findByLevel();
        return response()->json($result);
    }

}
