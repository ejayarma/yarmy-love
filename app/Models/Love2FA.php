<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Love2FA extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'love2fas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_name',
        'sender_email',
        'recipient_name',
        'recipient_pincode',
        'gift_description',
        'message',
        'slug',
        'max_attempts',
        'hints',
        'hints_viewed',
        'is_revealed',
        'revealed_at',
        'is_unlocked',
        'unlocked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hints' => 'array',
        'hints_viewed' => 'integer',
        'is_revealed' => 'boolean',
        'is_unlocked' => 'boolean',
        'revealed_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'sender_name', // Hidden until correctly guessed
        'sender_email',
        'recipient_pincode',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($love2fa) {
            if (empty($love2fa->slug)) {
                $love2fa->slug = Str::random(32);
            }
            if (empty($love2fa->max_attempts)) {
                $love2fa->max_attempts = 5;
            }
        });
    }

    /**
     * Get all attempts for this Love2FA.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(Love2FAAttempt::class, 'love2fa_id');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Verify the recipient's PIN code.
     */
    public function verifyPincode(string $pincode): bool
    {
        return $pincode === $this->recipient_pincode;
    }

    /**
     * Check if the guessed name matches the sender's name.
     * Case-insensitive and trims whitespace.
     */
    public function verifyName(string $guessedName): bool
    {
        $needle = strtolower(trim($guessedName));
        $haystack = strtolower(trim($this->sender_name));

        return ! empty($needle) && $needle === $haystack
        || ! empty($needle) && Str::contains($haystack, $needle);
    }

    /**
     * Unlock the Love2FA with PIN.
     */
    public function unlock(): void
    {
        if (! $this->is_unlocked) {
            $this->update([
                'is_unlocked' => true,
                'unlocked_at' => now(),
            ]);
        }
    }

    /**
     * Get remaining attempts.
     */
    public function getRemainingAttemptsAttribute(): int
    {
        return max(0, $this->max_attempts - $this->attempts()->count());
    }

    /**
     * Check if attempts are exhausted.
     */
    public function hasAttemptsRemaining(): bool
    {
        return $this->remaining_attempts > 0;
    }

    /**
     * Check if the mystery has been solved (name guessed correctly).
     */
    public function isSolved(): bool
    {
        return $this->is_revealed || $this->attempts()->where('is_correct', true)->exists();
    }

    /**
     * Mark as revealed (name was guessed correctly).
     */
    public function markAsRevealed(): void
    {
        $this->update([
            'is_revealed' => true,
            'revealed_at' => now(),
        ]);
    }

    /**
     * Increment hints viewed counter.
     */
    public function incrementHintsViewed(): void
    {
        $this->increment('hints_viewed');
    }

    /**
     * Record a name guess attempt.
     */
    public function recordAttempt(string $guessedName, ?string $ipAddress = null, ?string $userAgent = null): Love2FAAttempt
    {
        $isCorrect = $this->verifyName($guessedName);

        $attempt = $this->attempts()->create([
            'guessed_name' => trim($guessedName),
            'is_correct' => $isCorrect,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        if ($isCorrect) {
            $this->markAsRevealed();
        }

        return $attempt;
    }

    /**
     * Get the last attempt.
     */
    public function getLastAttemptAttribute(): ?Love2FAAttempt
    {
        return $this->attempts()->latest()->first();
    }

    /**
     * Get total attempts count.
     */
    public function getTotalAttemptsAttribute(): int
    {
        return $this->attempts()->count();
    }

    /**
     * Get correct attempts count.
     */
    public function getCorrectAttemptsAttribute(): int
    {
        return $this->attempts()->where('is_correct', true)->count();
    }

    /**
     * Get incorrect attempts count.
     */
    public function getIncorrectAttemptsAttribute(): int
    {
        return $this->attempts()->where('is_correct', false)->count();
    }

    /**
     * Get safe data for public view (before PIN entry).
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'recipient_name' => $this->recipient_name,
            'slug' => $this->slug,
            'is_unlocked' => $this->is_unlocked,
        ];
    }

    /**
     * Get safe data for unlocked view (after PIN, before name guessed).
     */
    public function toUnlockedArray(): array
    {
        return [
            'id' => $this->id,
            'recipient_name' => $this->recipient_name,
            'gift_description' => $this->gift_description,
            'message' => $this->message,
            'max_attempts' => $this->max_attempts,
            'remaining_attempts' => $this->remaining_attempts,
            'total_attempts' => $this->total_attempts,
            'hints' => $this->hints,
            'hints_viewed' => $this->hints_viewed,
            'is_revealed' => $this->is_revealed,
            'slug' => $this->slug,
        ];
    }

    /**
     * Get full data (after correct guess).
     */
    public function toRevealedArray(): array
    {
        return [
            'id' => $this->id,
            'sender_name' => $this->sender_name,
            'recipient_name' => $this->recipient_name,
            'gift_description' => $this->gift_description,
            'message' => $this->message,
            'max_attempts' => $this->max_attempts,
            'total_attempts' => $this->total_attempts,
            'hints' => $this->hints,
            'is_revealed' => $this->is_revealed,
            'revealed_at' => $this->revealed_at,
            'slug' => $this->slug,
        ];
    }

    /**
     * Scope a query to only include revealed Love2FAs.
     */
    public function scopeRevealed($query)
    {
        return $query->where('is_revealed', true);
    }

    /**
     * Scope a query to only include unrevealed Love2FAs.
     */
    public function scopeUnrevealed($query)
    {
        return $query->where('is_revealed', false);
    }

    /**
     * Scope a query to only include unlocked Love2FAs.
     */
    public function scopeUnlocked($query)
    {
        return $query->where('is_unlocked', true);
    }

    /**
     * Scope a query to only include locked Love2FAs.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_unlocked', false);
    }
}
