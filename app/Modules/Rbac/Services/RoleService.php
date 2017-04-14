<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\User;
use DCN\RBAC\Models\Role;
use Request;

class RoleService {

	public function getList() {
		Request::flash();
		$orderBy = Request::input('orderBy', 'id');
		$sort = Request::input('sort', 'desc');
		$pid = Request::input('id');

		$userId = Request::input('uid', 0);
		$roleIds = [];

		if ($userId) {
			$user = User::find($userId);
			if ($user) {
				$roles = $user->getRoles();
				foreach ($roles as $key => $val) {
					$roleIds[] = $val->id;
				}
			}
		}

		$page = (int) Request::input('pageNumber', 1);
		$length = (int) Request::input('pageSize', 10);
		$start = ($page - 1) * $length;

		$roles = $pid ? Role::where('parent_id', $pid)->get()->toArray() : Role::all()->toArray();
		$roles = $this->buildTreeData($roles, 0, $roleIds);

		return $roles;
	}

	private function buildTreeData($roles, $pid = 0, $roleIds = []) {
		$tree = [];

		foreach ($roles as $key => $val) {
			if ($val['parent_id'] == $pid) {
				$children = $this->buildTreeData($roles, $val['id'], $roleIds);
				$children && $val['children'] = $children;
				if (in_array($val['id'], $roleIds)) {
					$val['checked'] = true;
				}
				$val['text'] = $val['name'];
				$tree[] = $val;
			}
		}

		return $tree;
	}

	public function find($id) {
		return Role::find((int) $id);
	}

	public function destroy($id = 0) {
		$ids = $id ? $id : Request::input('ids', 0);
		// 数据库建立了级联删除，所以角色权限对应表相关数据会同时删除
		return Role::destroy($ids);
	}

	public function save($request) {
		$roleId = $request->input('id', 0);
		$role = Role::firstOrNew(['id' => $roleId]);
		$role->name = $request->name;
		$role->slug = $request->slug;
		$role->description = $request->description;
		$role->parent_id = $request->pid ? $request->pid : null;
		$role->save();

		$permissionIds = $request->permissionIds;
		if ($permissionIds) {
			$permissionIds = explode(',', $permissionIds);
			$existsPermissions = $role->permissions()->get(['permissions.id'])->toArray();
			$existsPermissions = array_map(function ($item) {
				unset($item['pivot']);
				return $item;
			}, $existsPermissions);
			$existsPermissions = array_flatten($existsPermissions);

			$needRemove = array_diff($existsPermissions, $permissionIds);
			$needAdd = array_diff($permissionIds, $existsPermissions);

			foreach ($needRemove as $key => $val) {
				$role->detachPermission($val);
			}
			foreach ($needAdd as $key => $val) {
				$role->attachPermission($val);
			}
		}

		return $role;
	}

	public function selectTagTreeWrapper($list, $pid = 0, $level = 0, $html = '┆┄') {
		static $tree = [];

		foreach ($list as $val) {
			if ($val['parent_id'] == $pid) {
				$val['level'] = $level;
				$val['html'] = str_repeat($html, $level);
				$tree[] = $val;
				$this->selectTagTreeWrapper($list, $val['id'], $level + 1);
			}
		}
		return $tree;
	}
}
