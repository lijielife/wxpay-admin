<?php

namespace App\Modules\Transaction\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Transaction\Services\TransactionService;

class TransactionController extends Controller
{

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return view('transaction::transaction.list');
    }

    public function datagrid()
    {
        $result = $this->transactionService->getList();
        return response()->json($result);
    }

}
