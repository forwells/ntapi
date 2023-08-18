<?php

use App\Models\AdminUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create(config('rbac.tables.users'), function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('email_verified_at')->default('');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create(config('rbac.tables.roles'), function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label')->unique();
            $table->timestamps();
        });

        Schema::create(config('rbac.tables.permissions'), function (Blueprint $table) {
            $table->id();
            $table->string('parent')->nullable();
            $table->string('slug')->unique();
            $table->string('label')->unique();
            $table->string('http_method')->nullable();
            $table->string('http_path');
            $table->bigInteger('order');
            $table->timestamps();

            $table->index(['parent']);
        });

        Schema::create(config('rbac.tables.menus'), function (Blueprint $table) {
            $table->id();
            $table->string('parent')->nullable();
            $table->string('label')->unique();
            $table->string('title')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->bigInteger('order');
            $table->string('uri')->unique();
            $table->timestamps();
            $table->index(['parent']);
        });

        Schema::create(config('rbac.tables.user_roles'), function (Blueprint $table) {
            $table->string('user_id');
            $table->string('role_id');

            $table->index(['user_id', 'role_id']);
        });

        Schema::create(config('rbac.tables.role_permissions'), function (Blueprint $table) {
            $table->string('role_id');
            $table->string('permission_id');

            $table->index(['role_id', 'permission_id']);
        });
        Schema::create(config('rbac.tables.role_menus'), function (Blueprint $table) {
            $table->string('role_id');
            $table->string('menu_id');

            $table->index(['role_id', 'menu_id']);
        });

        DB::table(config('rbac.tables.users'))->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists(config('rbac.tables.users'));
        Schema::dropIfExists(config('rbac.tables.roles'));
        Schema::dropIfExists(config('rbac.tables.permissions'));
        Schema::dropIfExists(config('rbac.tables.menus'));
        Schema::dropIfExists(config('rbac.tables.user_roles'));
        Schema::dropIfExists(config('rbac.tables.role_permissions'));
        Schema::dropIfExists(config('rbac.tables.role_menus'));
    }
};
