<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function divisionInfo()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function districtInfo()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function upazilaInfo()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class,'fiscal_year_id','id');
    }
    
    public function fiscalYearInfo()
    {
        return $this->belongsTo(FiscalYear::class,'fiscal_year_id','id');
    }
    
    public function refereneOrder()
    {
        return $this->belongsTo(Order::class,'id','ref_order_id');
    }
    
    public function officeInfo()
    {
        return $this->belongsTo(Office::class,'office_id','id');
    }

    public function sendToOffice()
    {
        return $this->hasOneThrough(
            Office::class,
            Order::class,
            'ref_order_id',
            'id',
            'id',
            'office_id'
        );
    }
}
