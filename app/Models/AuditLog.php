<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, string $description, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? (auth()->id() ?? null),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
