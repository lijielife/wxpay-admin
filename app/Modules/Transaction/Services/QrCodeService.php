<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Transaction\Models\QrBatch;
use App\Modules\Transaction\Models\QrCode;
use Auth;
use DB;
use File;
use QrCode as QRGenarator;
use Ramsey\Uuid\Uuid;
use Request;

class QrCodeService
{
    public function getBatchsList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $length = (int) Request::input('pageSize', 10);
        $channelId = (int) Request::input('channel_id');

        $batchs = QrBatch::with('channel', 'operator')->roleLimit($channelId)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $batchs['rows'] = $batchs['data'];
        $batchs['pages'] = $batchs['last_page'];
        unset($batchs['per_page'], $batchs['current_page'], $batchs['last_page'], $batchs['next_page_url'], $batchs['prev_page_url'], $batchs['from'], $batchs['to'], $batchs['data']);

        return $batchs;
    }

    public function getQrCodesList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $batchId = Request::input('batch_id', 0);
        $sort = Request::input('sort', 'desc');
        $length = (int) Request::input('pageSize', 10);

        $codes = QrCode::with('merchant', 'cashier')->where('batch_id', $batchId)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $codes['rows'] = $codes['data'];
        $codes['pages'] = $codes['last_page'];
        unset($codes['per_page'], $codes['current_page'], $codes['last_page'], $codes['next_page_url'], $codes['prev_page_url'], $codes['from'], $codes['to'], $codes['data']);

        return $codes;
    }

    public function make($request)
    {
        DB::beginTransaction();
        $result = false;
        try {
            $qrBatch = new QrBatch();
            $qrBatch->fill($request->all());
            $qrBatch->user_id = Auth::user()->id;
            $qrBatch->save();

            $generator = QRGenarator::format('png')->margin(0)->size(630);
            $logoPath = storage_path() . str_replace('/storage', '', $request->merchant_logo);
            $serils = [];

            if (!empty($request->merchant_logo) && file_exists($logoPath)) {
                $generator = $generator->merge($request->merchant_logo, 0.2);
            }

            File::makeDirectory(storage_path() . '/qrcode/' . $qrBatch->id, 0754, true, true);
            for ($i = 0; $i < $request->num; $i++) {
                $uuid1 = Uuid::uuid1();
                $serils[] = $serialNo = $uuid1->toString();
                $target = storage_path() . '/qrcode/' . $qrBatch->id . '/' . $serialNo . '.png';
                $generator->generate('http://pay.prorigine.com/jspay/pay?payNo=' . $serialNo, $target);
            }

            foreach ($serils as $key => $val) {
                $qrcode = new QrCode();
                $qrcode->batch_id = $qrBatch->id;
                $qrcode->serial_no = $val;
                $qrcode->save();
            }

            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        } finally {
            return $result;
        }
    }

    public function export()
    {
        $batchId = Request::input('batch_id');
        if (!$batchId) {
            return '参数错误';
        }
        $result = QrCode::select('serial_no')->where('status', 'not')->where('batch_id', $batchId)->get()->toArray();

        if (empty($result)) {
            return '导出失败！';
        }

        $path = storage_path() . '/qrcode/' . $batchId;
        $zipName = $batchId . '-' . date('YmdHis') . '.zip';
        $zip = new \ZipArchive();
        // OVERWRITE：若文件存在则覆盖；CREATE：若文件存在则往里面添加；文件生成在public目录下
        if ($zip->open($zipName, \ZipArchive::CREATE) === true) {
            foreach ($result as $key => $val) {
                // echo "{$path}/{$val['serial_no']}.png<br>";
                $zip->addFile("{$path}/{$val['serial_no']}.png", "{$val['serial_no']}.png");
            }
            $zip->close();
            //清空（擦除）缓冲区并关闭输出缓冲
            ob_end_clean();
            //下载建好的.zip压缩包
            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: binary");
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename=' . $zipName);
            header('Content-Length: ' . filesize($zipName));
            error_reporting(0);
            readfile($zipName);
            flush();
            exit;
        }
        echo '导出失败';exit;
    }

    public function unbind($id)
    {
        $qrcode = QrCode::find($id);
        if (is_null($qrcode)) {
            return '该二维码不存在';
        }
        // 渠道商只能解绑当前用户商户下的二维码
        // 超级管理员跳过
        // 收银员无权进入
        $user = Auth::user();

        if ($user->is('cashier')) {
            return '无权进行解绑操作！';
        }

        if ($user->is('channel.manager')) {
            if ($user->mapping_type == 'channels') {
                if ($qrcode->channel_id != $user->mapping_id) {
                    return '无权进行解绑操作！';
                }
            } else {
                return '无权进行解绑操作！';
            }

        }

        // 该二维码解绑后可以无限使用，对公测试用
        if ($id == 91) {
            $qrcode->channel_id = null;
            $qrcode->merchant_id = null;
            $qrcode->cashier_id = null;
            $qrcode->user_id = null;
            $qrcode->binded_at = null;
            $qrcode->merchant_no = null;
            $qrcode->status = 'not';
        } else {
            $qrcodes->status = 'unbind';
        }
        $qrcode->save();

        return true;
    }
}
