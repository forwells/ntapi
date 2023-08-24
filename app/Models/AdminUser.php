<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\Admin;
use App\Models\Admin\Menu;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kra8\Snowflake\HasSnowflakePrimary;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasDateTimeFormatter, HasSnowflakePrimary;

    protected $table = 'admin_users';

    protected $appends = ['key'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id' => 'string'
    ];

    protected function getKeyAttribute()
    {
        return (string) $this->id;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, config('rbac.tables.user_roles'), 'user_id', 'role_id');
    }

    public static function administrator()
    {
        $user = self::find(Admin::ADMINISTRATOR);
        $menus = Menu::all();
        $permissions = Permission::all();
        return [
            ...$user->toArray(),
            'menus' => $menus,
            'permissions' => $permissions
        ];
    }
}
