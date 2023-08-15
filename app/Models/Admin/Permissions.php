<?php

namespace App\Models\Admin;

use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $table = 'admin_permissions';

    protected $fillable = [
        'parent',
        'slug',
        'label',
        'http_method',
        'http_path',
        'order',
    ];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, config('rbac.tables.role_permissions'));
    }
}
