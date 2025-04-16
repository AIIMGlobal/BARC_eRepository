<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id', 'id');
    }
    
    public function projectInfoData()
    {
        return $this->belongsTo(ProjectInfo::class, 'id', 'project_id');
    }
    
    public function projectDocumentData()
    {
        return $this->hasMany(Document::class, 'project_id', 'id');
    }
}
