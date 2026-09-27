<?php

namespace Modules\Auth\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

use Modules\Auth\Database\Factories\RoleFactory;

class Role extends ModelsRole
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
