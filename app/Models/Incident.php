<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'type',
        'status',
        'responder_location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responseLogs()
    {
        return $this->hasMany(ResponseLog::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
