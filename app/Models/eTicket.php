<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eTicket extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(eTicketType::class, 'ticket_type_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by', 'id');
    }

    public function solvedBy()
    {
        return $this->belongsTo(User::class, 'solved_by', 'id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
