<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'responder_id',
        'action_taken'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }
}
