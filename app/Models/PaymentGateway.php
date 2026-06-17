<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['name', 'name_bn', 'slug', 'account_number', 'instructions', 'instructions_bn', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
