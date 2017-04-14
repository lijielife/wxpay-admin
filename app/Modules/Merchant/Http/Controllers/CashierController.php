<?php

namespace App\Modules\Merchant\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Http\Requests\CashierRequest;
use App\Modules\Merchant\Models\Cashier;
use App\Modules\Merchant\Services\CashierService;
use Request;

class CashierController extends Controller
{

    public function __construct(CashierService $cashierService)
    {
        $this->cashierService = $cashierService;
    }

    public function index()
    {
        return view('merchant::cashier.list');
    }

    public function datagrid()
    {
        $result = $this->cashierService->getList();
        return response()->json($result);
    }

    public function add()
    {
        return view('merchant::cashier.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $data['cashier'] = Cashier::with('merchant', 'adminAccount')->find($id);
        return view('merchant::cashier.edit', $data);
    }

    public function save(CashierRequest $cashierRequest)
    {
        $result = $this->cashierService->save($cashierRequest);
        return response()->json(['success' => $result === true, 'msg' => $result === false ? '保存失败！' : ($result === true ? '保存成功！' : $result)]);
    }

    public function destroy()
    {
        $result = $this->cashierService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result === true ? '删除成功' : $result];
        return response()->json($data);
    }

}
