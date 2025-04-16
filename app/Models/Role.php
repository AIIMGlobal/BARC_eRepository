<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function permissions()
    {        
        return $this->hasMany(RolePermission::class, 'role_id','id');
    }
}
