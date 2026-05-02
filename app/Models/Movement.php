<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'vessel_id',
        'exit_id',
        'user_id',
        'type',
        'notes',
        'moved_at',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function exit()
    {
        return $this->belongsTo(ExitGate::class, 'exit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
