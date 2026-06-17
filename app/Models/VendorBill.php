<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBill extends Model
{
    protected $fillable = ['bill_code', 'vendor_name', 'notes', 'bill_date', 'due_date', 'amount', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
        'bill_date' => 'date',
        'due_date' => 'date',
    ];
}
