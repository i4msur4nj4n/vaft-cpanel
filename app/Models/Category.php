<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name_en', 'name_bn', 'description', 'icon'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_en . ' | ' . $this->name_bn;
    }
}
