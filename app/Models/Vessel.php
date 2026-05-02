<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'name',
        'vessel_number',
        'barcode',
        'status',
        'description',
        'image',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function currentMovement()
    {
        return $this->hasOne(Movement::class)
            ->where('type', 'exit')
            ->latestOfMany('moved_at');
    }

    public function latestMovement()
    {
        return $this->hasOne(Movement::class)
            ->latestOfMany('moved_at');
    }

    public function scopeInside($query)
    {
        return $query->where('status', 'inside');
    }

    public function scopeOutside($query)
    {
        return $query->where('status', 'outside');
    }
}
