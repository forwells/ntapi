<?php

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
        if (!Schema::hasTable(config('rbac.tables.user'))) {
            Schema::create(config('rbac.tables.user'), function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        Schema::create(config('rbac.tables.role'), function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('label');
        });

        Schema::create(config('rbac.tables.permissions'), function (Blueprint $table) {
            $table->id();
            $table->string('parent');
            $table->string('slug');
            $table->string('label');
            $table->string('http_method')->nullable();
            $table->string('http_path');
            $table->bigInteger('order');
            $table->timestamps();

            $table->index(['parent']);
        });

        Schema::create(config('rbac.tables.menus'), function (Blueprint $table) {
            $table->id();
            $table->string('parent');
            $table->string('label');
            $table->string('title')->nullable();
            $table->string('icon')->nullable();
            $table->bigInteger('order');
            $table->string('uri');

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
