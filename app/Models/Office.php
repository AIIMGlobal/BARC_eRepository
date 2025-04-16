<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id', 'id');
    }
    
    public function districtData()
    {
        return $this->hasMany(District::class, 'division_id', 'division_id');
    }
    
    public static function areaInfos($division_ids,$district_ids)
    {
        $office_query = Office::latest();
        
        $data['division_ids'] = $division_ids;

        if ($division_ids != '') {
            $data['districts_ids_office_ids'] = $office_query->whereIn('division_id',explode(',',$division_ids))->where('status',1)->pluck('id','district_id');
        }
        
        if ($district_ids != '') {
            $data['upazila_ids_office_ids'] = $office_query->whereIn('district_id',explode(',',$district_ids))->where('status',1)->pluck('id','upazila_id');
        }
        
        return $data;
    }
    
    public static function officeIds($divisionIds)
    {
        $office_query = Office::latest();

        if ($divisionIds != '') {
            $office_query->whereIn('division_id',explode(',',$divisionIds));
        }

        return $office_query->where('status',1)->select('id')->get()->pluck('id','id');
    }

    // public function officeName($id)
    // {
    //     $officeName = Office::select('name')->where('id', $id)->first();
    //     return $officeName->name;
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
