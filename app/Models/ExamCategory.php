<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    use HasFactory;

    public function examName($id)
    {
        $examName = Exam::select('name_en')->where('id', $id)->first();
        return $examName->name_en;
    }
}
