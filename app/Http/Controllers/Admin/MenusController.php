<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Menu;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * 获取所有菜单
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $top = Menu::with('child')->whereNull('parent')->get();
        return response()->json($top);
    }

    /**
     * 创建菜单
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'parent' => '',
            'label' => 'required|unique:admin_menus,uri,except,id',
            'title' => '',
            'icon' => '',
            'uri' => 'required|unique:admin_menus,uri,except,id'
        ]);

        $data = $request->only(['parent', 'label', 'title', 'icon', 'uri']);

        $menu = Menu::make();
        $menu->fill($data);
        $menu->save();

        return response()->json($menu);
    }

    /**
     * 获取菜单信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $menu = Menu::find($id);
        return response()->json($menu);
    }

    /**
     * 更新菜单信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'parent' => '',
            'label' => 'required|unique:admin_menus,uri,except,id',
            'title' => '',
            'icon' => '',
            'uri' => 'required|unique:admin_menus,uri,except,id'
        ]);

        $data = $request->only(['parent', 'label', 'title', 'icon', 'uri']);

        $menu = Menu::find($id);

        $menu->fill($data);

        $menu->save();

        return response()->json($menu);
    }

    /**
     * 删除菜单
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            Menu::destroy($id);

            return response()->json([]);
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
