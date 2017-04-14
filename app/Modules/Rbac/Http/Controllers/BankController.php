<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\BankRequest;
use App\Modules\Rbac\Models\Bank;
use App\Modules\Rbac\Services\BankService;
use Request;

class BankController extends Controller
{

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function index()
    {
        return view('rbac::bank.list');
    }

    public function add()
    {
        return view('rbac::bank.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        return view('rbac::bank.edit', ['bank' => Bank::find($id)]);
    }

    public function save(BankRequest $bankRequest)
    {
        $result = $this->bankService->save($bankRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->bankService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function datagrid()
    {
        $result = $this->bankService->getList();
        return response()->json($result);
    }

}
