<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Post extends Model
{
    use HasFactory;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id', 'id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function postWiseEmployeeCount($id)
    {
        return Post::where('post_category_id', $id)->where('status', 1)->count();
    }

    public function postWiseEmployeeCountCopy($id)
    {
        return DB::table('user_infos')
        ->join('users', 'user_infos.user_id', '=',  'users.id')
        ->join('posts', 'user_infos.post_id', '=', 'posts.id')
        ->where('posts.post_category_id', '=', $id)->whereNotNull('user_infos.post_id')
        ->where('users.status', 1)
        ->where(function($query) {
            $query->where('user_infos.is_retire', 0)
            ->orWhere('user_infos.is_retire', NULL);
        })
        ->select(DB::raw('count("user_infos.post_id") as post_count'))
        ->get()->first();
    }


}
