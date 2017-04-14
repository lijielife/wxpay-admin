<?php

namespace App\Modules\Merchant\Services;

use App\Modules\Channel\Models\BillingAccount;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Models\MerchantPayment;
use App\Modules\Rbac\Models\User;
use App\Services\BaseService;
use Auth;
use Carbon\Carbon;
use DB;
use DCN\RBAC\Models\Role;
use Mail;
use Request;

class MerchantService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->tag = Request::segment(2);
    }

    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $type = Request::input('type') ? explode('|', Request::input('type')) : null;
        $activate = Request::input('activate');
        $length = (int) Request::input('pageSize', 10);

        // 如果是门店，类型限制为直营店和加盟店
        if ($this->tag == 'store') {
            $type = ['direct', 'chain'];
        }

        $merchants = Merchant::with('parent')->with('channel', 'department')->roleLimit()->typeIn($type)->activateStatus($activate)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $merchants['rows'] = $merchants['data'];
        $merchants['pages'] = $merchants['last_page'];
        unset($merchants['per_page'], $merchants['current_page'], $merchants['last_page'], $merchants['next_page_url'], $merchants['prev_page_url'], $merchants['from'], $merchants['to'], $merchants['data']);

        return $merchants;
    }

    public function save($request)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $merchantId = $request->input('id', 0);
            $merchant = Merchant::firstOrNew(['id' => $merchantId]);

            $user = Auth::user();
            if ($request->examine_status) {
                $merchant->examine_status = $request->examine_status;
                $merchant->examine_at = new Carbon();
                $merchant->examine_by = $user->id;
                $merchant->examine_remark = $request->examine_remark;
            } else if ($request->activate_status) {
                $merchant->activate_status = $request->activate_status;
                $merchant->activate_at = new Carbon();
                $merchant->activate_by = $user->id;
                $merchant->activate_remark = $request->activate_remark;
            } else {
                $merchant->fill($request->all());
                $merchant->created_by = $user->id;
                $merchant->merchant_protocol_pic = array_values(array_filter($request->merchant_protocol_pic));
                // $merchant->secret_key =
                $merchant->pid = $request->pid ? $request->pid : null;
            }

            $exists = $merchant->exists;
            if (!$exists) {
                $merchant->serial_no = 'ME' . (new Carbon())->timestamp;
            }
            $merchant->save();

            if (!$exists) {
                $billingAccount = new BillingAccount();
                $billingAccount->fill($request->input('billing_account'));
                $billingAccount->save();

                $merchant->account()->attach($billingAccount->id, ['mapping_type' => 'merchants']);
            }

            // 激活的时候生成登陆用户并发送短信
            if ($request->activate_status == 'success') {
                $password = str_random(10);

                // 判断邮箱、手机是否被注册，如果是，提示错误
                $user = User::where('email', $merchant->email)->first();
                if (!is_null($user)) {
                    $result = '邮箱已被使用，无法创建用户！';
                    return;
                }
                $user = User::where('mobile', $merchant->manager_mobile)->first();
                if (!is_null($user)) {
                    $result = '手机/电话已被使用，无法创建用户！';
                    return;
                }

                $user = User::create([
                    'name' => $merchant->serial_no,
                    'email' => $merchant->email,
                    'mobile' => $merchant->manager_mobile,
                    'password' => bcrypt($password),
                    'mapping_type' => 'merchants',
                    'mapping_id' => $merchant->id,
                ]);
                // \Log::debug($user->email . ':' . $password);
                $role = Role::where('slug', 'merchant.manager')->first();
                $user->attachRole($role);

                // 发送密码通知短信
                if (preg_match('/^1\d{10}$/', $merchant->manager_mobile)) {
                    $this->sendTemplateSMS(parent::MERCHANT, [$merchant->manager_mobile], [$merchant->manager, $merchant->name, $password]);
                }
                Mail::send('mails.active', ['type' => 'merchant', 'realname' => $merchant->manager, 'name' => $merchant->name, 'password' => $password], function ($m) use ($merchant) {
                    $m->to($merchant->email, $merchant->manager)->subject('商户帐号激活通知!');
                });
            }

            DB::commit();

            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        } finally {
            return $result;
        }
    }

    public function saveStore($request)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $merchantId = $request->input('id', 0);
            $merchant = Merchant::firstOrNew(['id' => $merchantId]);

            $user = Auth::user();
            $exists = $merchant->exists;
            $bigMerchant = Merchant::find($request->pid);

            if (!$exists) {
                $bigMerchantArr = $bigMerchant->toArray();
                // $bigMerchantArr['type'] = $bigMerchant->attr('type');
                $bigMerchantArr['product_type'] = $bigMerchant->attr('product_type');

                // 这些字段不同步
                unset($bigMerchantArr['serial_no'], $bigMerchantArr['business_licence_pic'], $bigMerchantArr['identity_card_pic'], $bigMerchantArr['org_code_cert_pic'], $bigMerchantArr['merchant_protocol_pic'], $bigMerchantArr['examine_by'], $bigMerchantArr['examine_at'], $bigMerchantArr['examine_status'], $bigMerchantArr['examine_remark'], $bigMerchantArr['activate_by'], $bigMerchantArr['activate_at'], $bigMerchantArr['activate_status'], $bigMerchantArr['activate_remark']);

                // 同步上级大商户信息
                $merchant->fill($bigMerchantArr);
                $merchant->created_by = $user->id;
            }
            // 本次提交信息覆盖以上上级大商户信息
            $merchant->fill($request->all());

            $merchant->save();

            if (!$exists) {
                $merchantPayments = $bigMerchant->payments()->get();

                foreach ($merchantPayments as $key => $val) {
                    // 同步上级大商户支付配置信息
                    $merchantPayment = new MerchantPayment();
                    $merchantPayment->fill($val->toArray());
                    $merchantPayment->merchant_id = $merchant->id;
                    $merchantPayment->save();

                    // 这里只同步商户支付配置对应的结算账户信息，不同步商户自己对应的结算账户信息
                    $billingAccount = $val->account()->first();
                    $merchantPayment->account()->attach($billingAccount->id, ['mapping_type' => 'merchant_payments']);
                }
            }

            DB::commit();

            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        } finally {
            return $result;
        }
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        // 数据库建立了级联删除，所以商户支付方式表相关数据会同时删除

        return Merchant::destroy($ids);
    }

    public function getPayments()
    {
        $merchantId = Request::input('merchant_id', 0);
        if (!$merchantId) {
            return ['rows' => [], 'total' => 0, 'pages' => 0];
        }
        $length = (int) Request::input('pageSize', 10);
        $payments = MerchantPayment::with('payment', 'account.bank')->where('merchant_id', $merchantId)->paginate($length, ['*'], 'pageNumber')->toArray();

        $payments['rows'] = $payments['data'];
        $payments['pages'] = $payments['last_page'];
        unset($payments['per_page'], $payments['current_page'], $payments['last_page'], $payments['next_page_url'], $payments['prev_page_url'], $payments['from'], $payments['to'], $payments['data']);

        return $payments;
    }

    public function savePayment($request)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $merchantPaymentId = $request->input('id', 0);
            $merchantPayment = MerchantPayment::firstOrNew(['id' => $merchantPaymentId]);

            if ($request->activate_status) {
                $user = Auth::user();
                $merchantPayment->activate_status = $request->activate_status;
                $merchantPayment->activate_at = new Carbon();
                $merchantPayment->activate_by = $user->id;
                $merchantPayment->activate_remark = $request->activate_remark;
            } else {
                $merchantPayment->fill($request->all());
                $merchantPayment->billing_rate = $request->billing_rate / 1000;
            }
            $merchantPayment->save();

            $billingAccountId = $request->billing_account_id;

            if (!$billingAccountId) {
                $billingAccount = new BillingAccount();
                $billingAccount->fill($request->input('billing_account'));
                $billingAccount->save();

                $billingAccountId = $billingAccount->id;
            }

            $merchantPayment->account()->sync([$billingAccountId => ['mapping_type' => 'merchant_payments']]);

            DB::commit();

            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        } finally {
            return $result;
        }
    }

    public function getAllBillingAccounts(int $merchantId)
    {
        $ids = Merchant::find($merchantId)->payments()->lists('id');
        $where = '';

        if ($ids->count() > 0) {
            $ids = join(',', $ids->toArray());
            $where = " or (bam.mapping_type='merchant_payments' and bam.mapping_id in ({$ids}))";
        }

        $account = DB::select("select b.id as bank_id,b.name as bank_name,ba.* from cs_billing_account_mapping bam inner join cs_billing_accounts ba on bam.billing_account_id=ba.id inner join cs_banks b on ba.bank_id=b.id where (bam.mapping_type='merchants' and bam.mapping_id=?) {$where} group by ba.card_no", [$merchantId]);

        return $account;
    }

    public function getDepartments()
    {
        $id = Request::input('merchant_id', 0);
        $departmentId = Request::input('dept_id', 0);
        $departments = Merchant::find($id)->departments()->get()->toArray();
        $departments = $this->buildTreeData($departments, 0, $departmentId);

        // $departments = array_filter($departments, function ($val) use ($departmentId) {
        //     return $val['id'] != $departmentId;
        // });

        return $departments;
    }

    private function buildTreeData($departments, $pid = 0, $departmentId = 0)
    {
        $tree = [];

        foreach ($departments as $key => $val) {
            if ($val['pid'] == $pid) {
                // 忽略当前部门
                if ($val['id'] == $departmentId) {
                    continue;
                }
                $val['text'] = $val['name'];
                $children = $this->buildTreeData($departments, $val['id'], $departmentId);
                $children && $val['children'] = $children;
                $tree[] = $val;
            }
        }

        return $tree;
    }

}
