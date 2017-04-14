<?php

namespace App\Modules\Channel\Services;

use App\Modules\Channel\Models\BillingAccount;
use App\Modules\Channel\Models\Channel;
use App\Modules\Channel\Models\ChannelPayment;
use App\Modules\Rbac\Models\User;
use App\Services\BaseService;
use Auth;
use Carbon\Carbon;
use DB;
use DCN\RBAC\Models\Role;
use Mail;
use Request;

class ChannelService extends BaseService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');

        $length = (int) Request::input('pageSize', 10);

        $channels = Channel::with('parent')->roleLimit()->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $channels['rows'] = $channels['data'];
        $channels['pages'] = $channels['last_page'];
        unset($channels['per_page'], $channels['current_page'], $channels['last_page'], $channels['next_page_url'], $channels['prev_page_url'], $channels['from'], $channels['to'], $channels['data']);

        return $channels;
    }

    public function save($request)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $channelId = $request->input('id', 0);
            $channel = Channel::firstOrNew(['id' => $channelId]);

            if ($request->examine_status) {
                $user = Auth::user();

                $channel->examine_status = $request->examine_status;
                $channel->examine_at = new Carbon();
                $channel->examine_by = $user->id;
                $channel->examine_remark = $request->examine_remark;
            } else if ($request->activate_status) {
                $user = Auth::user();
                $channel->activate_status = $request->activate_status;
                $channel->activate_at = new Carbon();
                $channel->activate_by = $user->id;
                $channel->activate_remark = $request->activate_remark;
            } else {
                $channel->fill($request->all());
                $channel->pid = $request->pid ? $request->pid : null;
            }

            $exists = $channel->exists;
            if (!$exists) {
                $channel->invite_code = str_random(20);
                $channel->serial_no = 'CH' . (new Carbon())->timestamp;
            }
            $channel->save();

            if (!$exists) {
                $billingAccount = new BillingAccount();
                $billingAccount->fill($request->input('billing_account'));
                $billingAccount->save();

                $channel->account()->attach($billingAccount->id, ['mapping_type' => 'channels']);
            }

            // 激活的时候生成登陆用户并发送短信
            if ($request->activate_status == 'success') {
                $password = str_random(10);

                // 判断邮箱、手机是否被注册，如果是，提示错误
                $user = User::where('email', $channel->email)->first();
                if (!is_null($user)) {
                    $result = '邮箱已被使用，无法创建用户！';
                    return;
                }
                $user = User::where('mobile', $channel->tel)->first();
                if (!is_null($user)) {
                    $result = '手机/电话已被使用，无法创建用户！';
                    return;
                }

                // 数据库建立了唯一索引
                $user = User::create([
                    'name' => $channel->serial_no,
                    'email' => $channel->email,
                    'mobile' => $channel->tel,
                    'password' => bcrypt($password),
                    'mapping_type' => 'channels',
                    'mapping_id' => $channel->id,
                ]);
                $role = Role::where('slug', 'channel.manager')->first();
                $user->attachRole($role);

                // 如果电话栏填的手机，则发送密码通知短信
                if (preg_match('/^1\d{10}$/', $channel->tel)) {
                    $this->sendTemplateSMS(parent::CHANNEL, [$channel->tel], [$channel->manager, $channel->name, $password]);
                }
                Mail::send('mails.active', ['type' => 'channel', 'realname' => $channel->manager, 'name' => $channel->name, 'password' => $password], function ($m) use ($channel) {
                    $m->to($channel->email, $channel->manager)->subject('渠道帐号激活通知!');
                });
            }

            DB::commit();
            $result = true;
        }
        /*catch (QueryException $e) {
        DB::rollback();
        \Log::debug($e->getMessage());
        }*/
         catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
        } finally {
            return $result;
        }
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        // 数据库建立了级联删除，所以渠道支付方式表相关数据会同时删除

        return Channel::destroy($ids);
    }

    public function getPayments()
    {
        $channelId = Request::input('channel_id', 0);
        if (!$channelId) {
            return ['rows' => [], 'total' => 0, 'pages' => 0];
        }
        $length = (int) Request::input('pageSize', 10);
        $payments = ChannelPayment::with('payment')->where('channel_id', $channelId)->paginate($length, ['*'], 'pageNumber')->toArray();

        $payments['rows'] = $payments['data'];
        $payments['pages'] = $payments['last_page'];
        unset($payments['per_page'], $payments['current_page'], $payments['last_page'], $payments['next_page_url'], $payments['prev_page_url'], $payments['from'], $payments['to'], $payments['data']);

        return $payments;
    }

    public function savePayment($request)
    {
        $channelPaymentId = $request->input('id', 0);
        $channelPayment = ChannelPayment::firstOrNew(['id' => $channelPaymentId]);

        if ($request->activate_status) {
            $user = Auth::user();
            $channelPayment->activate_status = $request->activate_status;
            $channelPayment->activate_at = new Carbon();
            $channelPayment->activate_by = $user->id;
            $channelPayment->activate_remark = $request->activate_remark;
        } else {
            $channelPayment->fill($request->all());
            $channelPayment->billing_rate = $request->billing_rate / 1000;
        }

        return $channelPayment->save();
    }
}
