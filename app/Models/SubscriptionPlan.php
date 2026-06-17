<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name_en', 'name_bn', 'slug', 'price', 'period', 'features_en', 'features_bn'];

    protected $casts = [
        'features_en' => 'array',
        'features_bn' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }
}
