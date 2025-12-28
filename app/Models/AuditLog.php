<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'metadata',
        'status',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper para criar log de sucesso
     */
    public static function logSuccess(string $event, ?int $userId = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => $userId,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
            'status' => 'success',
        ]);
    }

    /**
     * Helper para criar log de falha
     */
    public static function logFailure(string $event, string $description, ?int $userId = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => $userId,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
            'status' => 'failure',
            'description' => $description,
        ]);
    }
}
