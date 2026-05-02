<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExitGate extends Model
{
    protected $table = 'exits';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class, 'exit_id');
    }
}
