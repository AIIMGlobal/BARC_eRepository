<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectCategory extends Model
{
    use HasFactory;

    public function subjectName($id)
    {
        $subjectName = Subject::select('name_en')->where('id', $id)->first();
        return $subjectName->name_en;
    }

}
