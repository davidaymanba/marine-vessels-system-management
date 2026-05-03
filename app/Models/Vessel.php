<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'name',
        'vessel_number',
        'vessel_type',
        'owner_name',
        'capacity',
        'maintenance_status',
        'barcode',
        'status',
        'description',
        'image',
        'archived_at',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
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

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}
