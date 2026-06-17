<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'name_bn', 'icon', 'slug', 'description', 'description_bn', 'status', 'capital', 'returns'];

    protected $casts = [
        'capital' => 'decimal:2',
        'returns' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getNetRoiAttribute(): float
    {
        return $this->returns - $this->capital;
    }
}
