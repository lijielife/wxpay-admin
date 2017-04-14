<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\User;
use Auth;
use Illuminate\Support\Str;
use Password;
use Request;

class UserService
{
    public function getList()
    {
        Request::flash();
        $email = Request::input('email');
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');

        $page = (int) Request::input('pageNumber', 1);
        $length = (int) Request::input('pageSize', 10);
        $start = ($page - 1) * $length;

        $users = User::likeEmail($email)->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $users = $users->toArray();
        $users['rows'] = $users['data'];
        $users['pages'] = $users['last_page'];
        unset($users['per_page'], $users['current_page'], $users['last_page'], $users['next_page_url'], $users['prev_page_url'], $users['from'], $users['to'], $users['data']);

        return $users;
    }

    public function find($id)
    {
        return User::find((int) $id);
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        // 数据库建立了级联删除，所以用户角色对应表和用户权限对应表相关数据会同时删除
        return User::destroy($ids);
    }

    public function save($request)
    {
        $userId = $request->input('id', 0);
        $user = User::firstOrNew(['id' => $userId]);
        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $permissionIds = $request->permission_ids ? explode(',', $request->permission_ids) : [];
        $permissions = $user->userPermissions()->get();
        $permissionIds2 = [];
        foreach ($permissions as $key => $val) {
            $permissionIds2[] = $val->id;
        }
        $permissionIds1 = array_diff($permissionIds, $permissionIds2); //需要新增的
        $permissionIds2 = array_diff($permissionIds2, $permissionIds); //需要删除的

        $roleIds = $request->role_ids ? explode(',', $request->role_ids) : [];
        $roles = $user->getRoles();
        $roleIds2 = [];
        foreach ($roles as $key => $val) {
            $roleIds2[] = $val->id;
        }
        $roleIds1 = array_diff($roleIds, $roleIds2); //需要新增的
        $roleIds2 = array_diff($roleIds2, $roleIds); //需要删除的

        array_walk($permissionIds1, function ($item) use ($user) {
            $user->attachPermission($item);
        });
        array_walk($permissionIds2, function ($item) use ($user) {
            $user->detachPermission($item);
        });
        array_walk($roleIds1, function ($item) use ($user) {
            $user->attachRole($item);
        });
        array_walk($roleIds2, function ($item) use ($user) {
            $user->detachRole($item);
        });

        return $user;
    }

    public function resetpwd($request)
    {
        $password = $request->password;
        $newPassword = $request->new_password;
        $repeatPassword = $request->repeat_password;

        if ($newPassword == $repeatPassword) {
            $user = Auth::user();

            // 验证旧密码
            $credentials = ['name' => $user->name, 'password' => $password];

            if (!Auth::validate($credentials)) {
                return '旧密码错误！';
            }

            $user->forceFill([
                'password' => bcrypt($newPassword),
                'remember_token' => Str::random(60),
            ])->save();

            Auth::login($user);

            return true;
        }

        return '两次输入新密码不匹配';
    }

}
