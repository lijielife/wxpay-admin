<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\IndustryRequest;
use App\Modules\Rbac\Models\Industry;
use App\Modules\Rbac\Services\IndustryService;
use Request;

class IndustryController extends Controller
{

    public function __construct(IndustryService $industryService)
    {
        $this->industryService = $industryService;
    }

    public function index()
    {
        return view('rbac::industry.list');
    }

    public function datagrid()
    {
        $result = $this->industryService->getList();
        return response()->json($result);
    }

    public function add()
    {
        return view('rbac::industry.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        return view('rbac::industry.edit', ['industry' => Industry::with('parent')->find($id)]);
    }

    public function save(IndustryRequest $industryRequest)
    {
        $result = $this->industryService->save($industryRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->industryService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }
}
