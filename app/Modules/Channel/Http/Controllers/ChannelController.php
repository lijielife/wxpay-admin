<?php

namespace App\Modules\Channel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Channel\Http\Requests\ChannelPaymentRequest;
use App\Modules\Channel\Http\Requests\ChannelRequest;
use App\Modules\Channel\Models\Channel;
use App\Modules\Channel\Models\ChannelPayment;
use App\Modules\Channel\Services\ChannelService;
use Auth;
use Request;

class ChannelController extends Controller
{

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    public function index()
    {
        return view('channel::channel.list');
    }

    public function datagrid()
    {
        $result = $this->channelService->getList();
        return response()->json($result);
    }

    public function add()
    {
        return view('channel::channel.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $data['channel'] = Channel::with('account')->with('parent')->find($id);

        return view('channel::channel.edit', $data);
    }

    public function save(ChannelRequest $channelRequest)
    {
        $result = $this->channelService->save($channelRequest);
        return response()->json(['success' => $result === true, 'msg' => $result === false ? '保存失败！' : ($result === true ? '保存成功！' : $result)]);
    }

    public function destroy()
    {
        $result = $this->channelService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function info()
    {
        $user = Auth::user();
        if ($user->mapping_type == 'channels') {
            $id = $user->mapping_id;
        } else {
            return response('当前用户不是渠道账户！');
            // $id = 3;
        }

        $data['channel'] = Channel::with('account.bank', 'parent', 'provinceInfo', 'cityInfo')->find($id);

        return view('channel::channel.info', $data);
    }

    public function examine()
    {
        $id = Request::input('id', 0);
        $data['channel'] = Channel::with('parent')->find($id);

        return view('channel::channel.examine', $data);
    }

    public function activate()
    {
        $id = Request::input('id', 0);
        $data['channel'] = Channel::with('parent')->find($id);

        return view('channel::channel.activate', $data);
    }

    public function payments()
    {
        $result = $this->channelService->getPayments();
        return response()->json($result);
    }

    public function addPayment()
    {
        $id = Request::input('channel_id', 0);
        $data['channel'] = Channel::find($id);
        return view('channel::channel.payment.add', $data);
    }

    public function editPayment()
    {
        $id = Request::input('channel_payment_id', 0);
        $data['channelPayment'] = ChannelPayment::with('channel')->with('payment')->find($id);
        return view('channel::channel.payment.edit', $data);
    }

    public function activatePayment()
    {
        $id = Request::input('id', 0);
        $data['channelPayment'] = ChannelPayment::with('channel')->with('payment')->find($id);
        return view('channel::channel.payment.activate', $data);
    }

    public function savePayment(ChannelPaymentRequest $channelPaymentRequest)
    {
        $result = $this->channelService->savePayment($channelPaymentRequest);
        return response()->json(['success' => $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

}
