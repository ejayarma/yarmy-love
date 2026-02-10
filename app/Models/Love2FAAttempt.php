<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Love2FAAttempt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'love2fa_attempts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'love2fa_id',
        'guessed_name',
        'is_correct',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the Love2FA that owns the attempt.
     */
    public function love2fa(): BelongsTo
    {
        return $this->belongsTo(Love2FA::class, 'love2fa_id');
    }

    /**
     * Check if this attempt was correct.
     */
    public function isCorrect(): bool
    {
        return $this->is_correct;
    }

    /**
     * Scope a query to only include correct attempts.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope a query to only include incorrect attempts.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Scope a query to order by most recent.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the formatted attempt time.
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M d, Y - h:i A');
    }

    /**
     * Get a human-readable status.
     */
    public function getStatusAttribute(): string
    {
        return $this->is_correct ? 'Correct' : 'Incorrect';
    }

    /**
     * Get status emoji.
     */
    public function getStatusEmojiAttribute(): string
    {
        return $this->is_correct ? '✅' : '❌';
    }

    /**
     * Get masked name for display (e.g., "John Doe" -> "J*** D**")
     */
    public function getMaskedNameAttribute(): string
    {
        if ($this->is_correct) {
            return $this->guessed_name;
        }

        $words = explode(' ', $this->guessed_name);
        return implode(' ', array_map(function ($word) {
            return substr($word, 0, 1) . str_repeat('*', max(0, strlen($word) - 1));
        }, $words));
    }
}
