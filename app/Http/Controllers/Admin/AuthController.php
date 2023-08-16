<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Sanctum Session 身份验证
 * @package App\Http\Controllers\Admin
 */
class AuthController extends Controller
{

    /**
     * 登录
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function login(Request $request)
    {
        $cd = $request->only(['email', 'password']);

        $user = AdminUser::where('email', $cd['email'])->first();

        if (!$user || !Hash::check($cd['password'], $user->password)) {
            return response()->json(['error' => '登录信息有误, 请确认账号密码是否正确'], 401);
        }

        $user = AdminUser::where('email', $cd['email'])->firstOrFail();

        $token = $user->createToken($user->email);

        $data = array_merge($user->toArray(), [
            'token' => $token->plainTextToken,
            'expires' => config('sanctum.expiration'),
        ]);
        return response()->json($data);
    }


    /**
     * 注册
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required',
            'roles' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $user_data = $request->only(['name', 'email', 'password']);
            $roles = $request->only(['roles']);
            $admin_user = AdminUser::make();
            $admin_user->fill($user_data);
            $admin_user->roles()->sync($roles);

            DB::commit();

            return response()->json($admin_user);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // $request->validate([''])
    }


    /**
     * 个人信息
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    /**
     * 登出
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            return response()->json(['msg' => '退出成功.']);
        } catch (\Exception $e) {
            logger('用户登出异常:' . $e->getMessage());
            return response()->json(['msg' => '退出登录失败'], 500);
        }
    }
}
