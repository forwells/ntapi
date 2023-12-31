<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $user = AdminUser::with(['roles', 'roles.permissions', 'roles.menus'])->where('email', $cd['email'])->firstOrFail();
        $token = $user->createToken($user->email, ['*'], now()->addMinutes(env('ADMIN_EXP')));

        if ($user->id == Admin::ADMINISTRATOR) {
            $user = AdminUser::administrator();
        }

        $data = array_merge(is_array($user) ? $user : $user->toArray(), [
            'token' => $token->plainTextToken,
            'expires' => env('ADMIN_EXP') * 60
        ]);
        return response()->json($data);
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
