<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public function districtName()
    {
        return $this->belongsTo(District::class, 'permanent_district_id', 'id');
    }
    public function presentDistrictName()
    {
        return $this->belongsTo(District::class, 'present_district_id', 'id');
    }
    public function presentDivision()
    {
        return $this->belongsTo(Division::class, 'present_division_id', 'id');
    }
    public function presentUpazila()
    {
        return $this->belongsTo(Upazila::class, 'present_upazila_id', 'id');
    }
    public function permanentDivision()
    {
        return $this->belongsTo(Division::class, 'permanent_division_id', 'id');
    }
    public function permanentDistrict()
    {
        return $this->belongsTo(District::class, 'permanent_district_id', 'id');
    }
    public function permanentUpazila()
    {
        return $this->belongsTo(Upazila::class, 'permanent_upazila_id', 'id');
    }
}
