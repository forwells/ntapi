<?php

namespace App\Models\Admin;

use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $table = 'admin_menus';

    protected $fillable = [
        'parent',
        'label',
        'title',
        'icon',
        'order',
        'uri',
    ];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, config('rbac.tables.role_menus'));
    }
}
