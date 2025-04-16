<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function employeeInfo()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function postInfo()
    {
        return $this->belongsTo(Post::class,'post_id','id');
    }
    
    public function userInfoData()
    {
        return $this->belongsTo(UserInfo::class,'user_id','user_id');
    }
    
    public function transferInfo()
    {
        return $this->belongsTo(Transfer::class,'transfer_id','id');
    }
}
