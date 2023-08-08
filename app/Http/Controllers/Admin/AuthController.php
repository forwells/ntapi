<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

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
        if (!$token = auth('admin')->attempt($cd)) {
            return response()->json(['error' => '登录信息有误, 请确认账号密码是否正确'], 401);
        }

        $user = AdminUser::where('email', $cd['email'])->firstOrFail();

        $token = $user->createToken($user->email);

        $data = array_merge($user, [
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
        return abort(500);
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
