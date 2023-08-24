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
        ], [
            'slug.unique' => '权限ID必须唯一'
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
        // dd($request->all());
        $request->validate(
            [
                'parent' => '',
                'slug' => 'required|unique:admin_permissions,slug,' . $id,
                'label' => 'required|unique:admin_permissions,label,' . $id,
                'http_method' => '',
                'http_path' => 'required'
            ],
            [
                'slug.unique' => '权限标识符已占用',
                'label.unique' => '权限标题已占用',
            ]
        );

        $data = $request->only(['parent', 'slug', 'label', 'http_method', 'http_path']);
        // dd($id,$data);
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
            Permission::destroy([$id]);
            return response()->json([]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 排序
     */
    public function sort(Request $request)
    {
        $request->validate(['tree' => 'required|array']);

        $tree = $request->input('tree');

        $this->sort_wheel($tree);

        return response()->json(['msg' => '保存排序成功']);
    }

    private function sort_wheel($tree = [])
    {
        // dd($tree);
        foreach ($tree as $node) {

            Permission::find($node['key'])->update(['parent' => $node['parent']]);
            if ($node['children'] ?? []) {
                $this->sort_wheel($node['children']);
            }
        }
    }
}
