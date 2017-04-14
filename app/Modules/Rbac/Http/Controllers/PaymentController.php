<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Services\PaymentService;

class PaymentController extends Controller
{

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return view('payment::payment.list');
    }

    public function datagrid()
    {
        $result = $this->paymentService->getList();
        return response()->json($result);
    }
}
