<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['booking_date', 'reference', 'memo', 'debit_account', 'debit_amount', 'credit_account', 'credit_amount'];

    protected $casts = [
        'booking_date' => 'date',
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];
}
