<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subjectInfo()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
    
    public function instituteInfo()
    {
        return $this->belongsTo(Insttitute::class, 'institute_id', 'id');
    }
    
    public function examInfo()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
    
    public function boardInfo()
    {
        return $this->belongsTo(Exam::class, 'board_id', 'id');
    }
    
    public function durationInfo()
    {
        return $this->belongsTo(Duration::class, 'duration_id', 'id');
    }
    
    public function academicExamInfo()
    {
        return $this->belongsTo(AcademicExamForm::class, 'academic_exam_form_id', 'id');
    }
}
