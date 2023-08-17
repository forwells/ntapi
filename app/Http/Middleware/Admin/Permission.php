<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $has_permission = $this->checkRoutePermission($request);

        if ($has_permission) {
            return $next($request);
        }

        return response()->json(['msg' => 'no-permission'], 401);
    }

    public function checkRoutePermission(Request $request)
    {
        /** @var \App\Models\AdminUser admin user */
        $user = auth('sanctum')->user();
        $except = config('rbac.auth.except');

        // except routes
        if ($request->is($except) && !$user) {
            return true;
        }

        return true;

        // administrators
        $is_administrators = in_array($user->id, env('ADMIN_USERS'));
        if ($is_administrators) {
            return true;
        }

        // role permission check
        $roles = $user->roles()->with(['permissions'])->get();

        return false;
    }
}
