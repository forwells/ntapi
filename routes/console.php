<?php

use App\Models\AdminUser;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Lcobucci\JWT\Signer\Key\InMemory;

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
    $username = $this->ask('Admin Username:');
    $email = $this->ask('Email:');
    $password = $this->secret('Password:');

    $user = AdminUser::create([
        'name' => $username,
        'email' => $email,
        'password' => Hash::make($password)
    ]);

    $this->info('Admin User Created.');
    $this->info('Username: ' . $user->name);
    $this->info('Email(account): ' . $user->email);
});
