<?php

namespace App\Models\Admin;

use App\Models\AdminUser;
use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $table = 'admin_roles';

    protected $fillable = [
        'slug', 'label'
    ];

    public function users()
    {
        return $this->belongsToMany(AdminUser::class, config('rbac.tables.user_roles'));
    }
}
