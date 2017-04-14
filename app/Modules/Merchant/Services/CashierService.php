<?php

namespace App\Modules\Merchant\Services;

use App\Modules\Merchant\Models\Cashier;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Rbac\Models\User;
use App\Services\BaseService;
use Carbon\Carbon;
use DB;
use DCN\RBAC\Models\Role;
use Mail;
use Request;

class CashierService extends BaseService
{
    public function getList()
    {
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $name = Request::input('name');

        $length = (int) Request::input('pageSize', 10);

        $cashiers = Cashier::with('merchant')->likeName($name)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber')->toArray();

        $cashiers['rows'] = $cashiers['data'];
        $cashiers['pages'] = $cashiers['last_page'];
        unset($cashiers['per_page'], $cashiers['current_page'], $cashiers['last_page'], $cashiers['next_page_url'], $cashiers['prev_page_url'], $cashiers['from'], $cashiers['to'], $cashiers['data']);

        return $cashiers;
    }

    public function save($request)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $cashierId = $request->input('id', 0);
            $cashier = Cashier::firstOrNew(['id' => $cashierId]);
            $cashier->fill($request->all());

            $exists = $cashier->exists;
            if (!$exists) {
                $cashier->serial_no = 'CA' . (new Carbon())->timestamp;
            }
            $cashier->save();

            // $oldEnabled = $cashier->getOriginal('enabled');

            // 生成帐号
            if (!$exists) {
                $password = str_random(10);

                // 判断邮箱、手机是否被注册，如果是，提示错误
                $user = User::where('email', $cashier->email)->first();
                if (!is_null($user)) {
                    $result = '邮箱已被使用，无法创建用户！';
                    return;
                }
                $user = User::where('mobile', $cashier->mobile)->first();
                if (!is_null($user)) {
                    $result = '手机/电话已被使用，无法创建用户！';
                    return;
                }

                $user = User::create([
                    'name' => $cashier->serial_no,
                    'email' => $cashier->email,
                    'mobile' => $cashier->mobile,
                    'password' => bcrypt($password),
                    'mapping_type' => 'cashiers',
                    'mapping_id' => $cashier->id,
                ]);

                $role = Role::where('slug', 'cashier')->first();
                $user->attachRole($role);

                $merchant = Merchant::find($cashier->merchant_id);

                if (preg_match('/^1\d{10}$/', $cashier->mobile)) {
                    $this->sendTemplateSMS(parent::CASHIER, [$cashier->mobile], [$cashier->name, $merchant->name, $password]);
                }

                Mail::send('mails.active', ['type' => 'cashier', 'realname' => $cashier->name, 'name' => $merchant->name, 'password' => $password], function ($m) use ($cashier) {
                    $m->to($cashier->email, $cashier->name)->subject('收银员帐号激活通知!');
                });
            }

            DB::commit();

            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $result;
    }

    public function destroy($id = 0)
    {
        DB::beginTransaction();
        $result = false;

        try {
            $ids = $id ? $id : Request::input('ids', 0);

            Cashier::destroy($ids);
            User::where('mapping_type', 'cashiers')->whereIn('mapping_id', $ids)->delete();

            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollback();
        } finally {
            return $result;
        }
    }

}
