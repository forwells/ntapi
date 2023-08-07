<?php

use App\Models\AdminUser;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('run', function () {
    /** @var Artisan $this */
    $this->call('serve', [
        '--port' => env('APP_PORT', 5999)
    ]);
})->purpose('Run server');

Artisan::command('admin:user', function () {
    $username = $this->ask('请输入用户名:');
    $email = $this->ask('请输入邮箱:');
    $password = $this->secret('请输入密码:');

    $user = AdminUser::create([
        'name' => $username,
        'email' => $email,
        'password' => Hash::make($password)
    ]);

    $this->info('用户创建成功');
    $this->info('用户名: ' . $user->name);
    $this->info('用户邮箱: ' . $user->email);
});
