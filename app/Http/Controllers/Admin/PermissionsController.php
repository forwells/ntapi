<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionsController extends Controller
{
    /**
     * 获取所有权限
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top = Permission::with('children')->whereNull('parent')->get();

        return response()->json($top);
    }

    /**
     * 存储权限
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'parent' => '',
            'slug' => 'required|unique:admin_permissions,slug,except,id',
            'label' => 'required|unique:admin_permissions,label,except,id',
            'http_method' => Rule::in(['', null, 'GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
            'http_path' => 'required'
        ]);

        $data = $request->only(['parent', 'slug', 'label', 'http_method', 'http_path']);

        $permission = Permission::make();
        $permission->fill($data);
        $permission->save();

        return response()->json($permission);
    }

    /**
     * 获取权限信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::find($id);

        return response()->json($permission);
    }

    /**
     * 更新权限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'parent' => '',
            'slug' => 'required|unique:admin_permissions,slug,except,id',
            'label' => 'required|unique:admin_permissions,label,except,id',
            'http_method' => Rule::in(['', null, 'GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
            'http_path' => 'required'
        ]);

        $data = $request->only(['parent', 'slug', 'label', 'http_method', 'http_path']);
        $permission = Permission::find($id);
        $permission->fill($data);
        $permission->save();

        return response()->json($permission);
    }

    /**
     * 删除权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            Permission::destroy($id);
            return response()->json([]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
