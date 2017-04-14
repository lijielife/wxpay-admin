<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Transaction\Models\Order;
use Auth;
use Request;

class TransactionService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $startTime = Request::input('startTime');
        $endTime = Request::input('endTime');
        $merchantId = Request::input('merchant_id');
        $channelId = Request::input('channel_id');
        $paymentId = Request::input('payment_id');
        $outTradeNo = Request::input('out_trade_no');
        $userId = Request::input('user_id');
        $deviceInfo = Request::input('device_info');
        $totalFeeStart = Request::input('total_fee_start');
        $totalFeeEnd = Request::input('total_fee_end');
        $status = Request::input('status');

        $length = 10;

        $user = Auth::user();
        $merchant = $user->mapping()->getResults();

        // 只有关联商户的用户才能收款
        if (is_null($merchant)) {
            return null;
        }
        // 如果是商户，查询他自己商户下的所有交易
        $where = "merchant_id = {$merchant->id}";
        // 如果是收银员，查询通过他的交易
        if ($merchant->getTable() == 'cashiers') {
            $where = "merchant_id = {$merchant->id}";
        } else if ($merchant->getTable() == 'channels') {
            $where = "channel_id = {$merchant->id}";
        } else {
            $whree = '';
        }

        if ($startTime && !$endTime) {
            $where .= " AND created_at >= '{$startTime}'";
        } else if (!$startTime && $endTime) {
            $where .= " AND created_at <= '{$endTime}'";
        } else if ($startTime && !$endTime) {
            return null; // 必须选择时间
        } else {
            $where .= " AND created_at >= '{$startTime}' AND created_at <= '{$endTime}'";
        }

        if ($merchantId) {
            $where .= " AND merchant_id = {$merchantId}";
        }
        if ($channelId) {
            $where .= " AND channel_id = {$channelId}";
        }
        if ($paymentId) {
            $where .= " AND payment_id = {$paymentId}";
        }
        if ($userId) {
            $where .= " AND user_id = {$userId}";
        }
        if ($deviceInfo) {
            $where .= " AND device_info LIKE '%{$deviceInfo}%'";
        }

        if ($totalFeeStart && !$totalFeeEnd) {
            $where .= " AND total_fee >= '{$totalFeeStart}'";
        } else if (!$totalFeeStart && $totalFeeEnd) {
            $where .= " AND total_fee <= '{$totalFeeEnd}'";
        } else if (!$totalFeeStart && !$totalFeeEnd) {

        } else {
            $where .= " AND total_fee >= '{$totalFeeStart}' AND total_fee <= '{$totalFeeEnd}'";
        }

        switch ($status) {
            case 'success':
                $where .= " AND return_code = 'SUCCESS' AND result_code = 'SUCCESS'";
                break;
            case 'not':
                $where .= " AND return_code IS NULL AND result_code IS NULL";
                break;
            case 'fail':
                $where .= " AND return_code = 'FAIL'";
                break;
        }

        $orders = Order::with('merchant')->whereRaw($where)->orderBy($orderBy, $sort)->simplePaginate($length, ['*'], 'pageNumber')->toArray();
        $total = Order::whereRaw($where)->count();
        $pages = ceil($total / $length);

        $orders['rows'] = $orders['data'];
        $orders['total'] = $total; // 为了避免查询，这里可以直接设置一个大数值，如999999999
        $orders['pages'] = $pages; // 为了避免查询，这里可以直接设置一个大数值，如999999999
        unset($orders['per_page'], $orders['current_page'], $orders['next_page_url'], $orders['prev_page_url'], $orders['data']);

        return $orders;
    }
}
