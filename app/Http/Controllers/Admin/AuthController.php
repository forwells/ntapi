<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // logger(json_encode($cd));
        // logger(json_encode(file_get_contents('php://input')));
        if (!$token = auth('admin')->attempt($cd)) {
            return response()->json(['error' => '登录信息有误, 请确认账号密码是否正确'], 401);
        }

        return $this->token_response($token);
    }


    public function register(Request $request)
    {
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    protected function token_response($token)
    {
        return response()->json([
            'token' => $token,
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
