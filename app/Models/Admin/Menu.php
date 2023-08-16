<?php

namespace App\Models\Admin;

use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kra8\Snowflake\HasSnowflakePrimary;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Menu extends Model implements Sortable
{
    use HasFactory, HasDateTimeFormatter, SortableTrait, HasSnowflakePrimary;

    protected $table = 'admin_menus';

    protected $hidden = ['pivot'];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true
    ];

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
        return $this->belongsToMany(Role::class, config('rbac.tables.role_menus'));
    }

    public function child()
    {
        return $this->hasMany(__CLASS__, 'parent', 'id');
    }
}
