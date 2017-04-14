<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\UserRequest;
use App\Modules\Rbac\Models\User;
use App\Modules\Rbac\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('rbac::user.list');
    }

    public function add()
    {
        return view('rbac::user.add');
    }

    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        return view('rbac::user.edit', ['user' => User::find($id)]);
    }

    public function save(UserRequest $userRequest)
    {
        $result = $this->userService->save($userRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function resetpwd(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('rbac::user.resetpwd');
        }
        $result = $this->userService->resetpwd($request);
        return response()->json(['success' => $result === true, 'msg' => $result === true ? '密码修改成功！' : $result]);
    }

    public function destroy()
    {
        $result = $this->userService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function getUsersList()
    {
        $result = $this->userService->getList();
        return response()->json($result);
    }

}
