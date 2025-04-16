<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
