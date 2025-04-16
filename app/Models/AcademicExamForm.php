<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicExamForm extends Model
{
    use HasFactory;

    public function examCategoryInfo()
    {
        return $this->belongsTo(ExamCategory::class,'exam_category_id','id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function examInfos($ids)
    {
        if ($ids) {
            $examName = Exam::whereIn('id', explode(',',$ids))->get();
            return $examName;
        } else {
            return array();
        }
    }

    public function subjectCategoryInfo()
    {
        return $this->belongsTo(SubjectCategory::class,'subject_category_id','id');
    }

    public function subjectInfos($ids)
    {
        if ($ids) {
            return Subject::whereIn('id', explode(',',$ids))->get();
        } else {
            return array();
        }
    }
    
    public function userAcademicRecord($user_id,$form_id)
    {
        if ($user_id) {
            return AcademicRecord::where('user_id', $user_id)->where('status',1)->where('academic_exam_form_id',$form_id)->where('status','!=',2)->first();
        } else {
            return '';
        }
    }
}
