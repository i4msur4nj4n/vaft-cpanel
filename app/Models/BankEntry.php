<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankEntry extends Model
{
    protected $fillable = ['booking_date', 'description', 'amount', 'flow_type', 'status', 'reference', 'matched_type', 'matched_id'];

    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
    ];
}
