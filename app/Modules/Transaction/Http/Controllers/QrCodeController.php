<?php

namespace App\Modules\Transaction\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Transaction\Http\Requests\QrBatchRequest;
use App\Modules\Transaction\Models\QrCode;
use App\Modules\Transaction\Services\QrCodeService;
use Request;

class QrCodeController extends Controller
{

    public function __construct(QrCodeService $qrcodeService)
    {
        $this->qrcodeService = $qrcodeService;
    }

    public function index()
    {
        return view('transaction::qrcode.list');
    }

    public function add()
    {
        return view('transaction::qrcode.add');
    }

    public function datagrid()
    {
        $result = $this->qrcodeService->getBatchsList();
        return response()->json($result);
    }

    public function qrcodes()
    {
        $result = $this->qrcodeService->getQrCodesList();
        return response()->json($result);
    }

    public function make(QrBatchRequest $request)
    {
        $result = $this->qrcodeService->make($request);
        return response()->json(['success' => $result === true, 'msg' => $result === false ? '生成二维码失败！' : ($result === true ? '生成二维码成功！' : $result)]);
    }

    public function export()
    {
        $this->qrcodeService->export();
    }

    public function unbind()
    {
        $id = Request::input('id');

        if (Request::method() == 'POST') {
            $result = $this->qrcodeService->unbind($id);
            return response()->json(['success' => $result === true, 'msg' => $result === false ? '解绑失败！' : ($result === true ? '解绑成功！' : $result)]);
        }

        $qrcode = QrCode::with('merchant')->find($id);

        return view('transaction::qrcode.unbind', ['qrcode' => $qrcode]);
    }

}
