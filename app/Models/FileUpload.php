<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    public function userName()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function officeName()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }
}
