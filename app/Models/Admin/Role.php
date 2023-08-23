<?php

namespace App\Models\Admin;

use App\Models\AdminUser;
use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kra8\Snowflake\HasSnowflakePrimary;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Role extends Model
{
    use HasFactory, HasDateTimeFormatter, HasSnowflakePrimary;

    protected $table = 'admin_roles';
    protected $appends = ['key'];

    protected $hidden = ['pivot'];

    protected $fillable = [
        'slug', 'label'
    ];

    protected function getKeyAttribute()
    {
        return $this->id;
    }

    public function users()
    {
        return $this->belongsToMany(AdminUser::class, config('rbac.tables.user_roles'));
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, config('rbac.tables.role_menus'));
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, config('rbac.tables.role_permissions'));
    }
}
