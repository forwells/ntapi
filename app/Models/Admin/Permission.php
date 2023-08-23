<?php

namespace App\Models\Admin;

use App\Models\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kra8\Snowflake\HasSnowflakePrimary;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Permission extends Model implements Sortable
{
    use HasFactory, HasDateTimeFormatter, SortableTrait, HasSnowflakePrimary;

    protected $table = 'admin_permissions';
    protected $appends = ['key', 'title'];

    protected $hidden = ['pivot'];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true
    ];

    protected $fillable = [
        'parent',
        'slug',
        'label',
        'http_method',
        'http_path',
        'order',
    ];

    protected function getKeyAttribute()
    {
        return $this->id;
    }
    protected function getTitleAttribute()
    {
        return $this->label;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, config('rbac.tables.role_permissions'));
    }

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent', 'id');
    }
}
