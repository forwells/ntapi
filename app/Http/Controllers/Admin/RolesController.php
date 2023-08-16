<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * 获取所有角色
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Role::all();

        return response()->json($all);
    }

    /**
     * 存储角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|regex:/[a-z0-9A-Z]+/',
            'label' => 'required|unique:admin_roles,label,except,id',
            'permissions' => 'required',
            'menus' => 'required'
        ]);

        $data = $request->only(['slug', 'label']);

        ['menus' => $menus, 'permissions' => $permissions] = $request->only(['menus', 'permissions']);

        try {
            DB::beginTransaction();

            $role = Role::make();
            $role->fill($data);
            $role->save();
            $role->menus()->sync($menus);
            $role->permissions()->sync($permissions);

            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    /**
     * 获取角色信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $role = Role::with(['menus', 'permissions'])->find($id);

        return response()->json($role);
    }

    /**
     * 更新角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => 'required|regex:/[a-z0-9A-Z]+/',
            'label' => 'required|unique:admin_roles,label,except,id',
            'permissions' => 'required',
            'menus' => 'required'
        ]);

        $data = $request->only(['slug', 'label']);

        ['menus' => $menus, 'permissions' => $permissions] = $request->only(['menus', 'permissions']);

        try {
            DB::beginTransaction();

            $role = Role::find($id);
            $role->fill($data);
            $role->save();
            $role->menus()->sync($menus);
            $role->permissions()->sync($permissions);

            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    /**
     * 删除角色
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($id);
            $role->menus()->sync([]);
            $role->permissions()->sync([]);
            $role->users()->sync([]);

            DB::commit();

            return response()->json([]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
}
