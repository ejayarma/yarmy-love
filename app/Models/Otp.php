<?php

namespace App\Models;

use App\Mail\OtpMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Otp extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'otps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate and send a new OTP.
     */
    public static function generate(string $email, string $type = 'login', int $expiryMinutes = 10): self
    {
        // Invalidate any existing unused OTPs for this email and type
        self::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->update(['is_used' => true, 'used_at' => now()]);

        // Generate 6-digit OTP
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP
        $otp = self::create([
            'email' => $email,
            'otp' => $otpCode,
            'type' => $type,
            'expires_at' => now()->addMinutes($expiryMinutes),
        ]);

        // Send email
        Mail::to($email)->send(new OtpMail($otp));

        return $otp;
    }

    /**
     * Verify an OTP.
     */
    public static function verify(string $email, string $otpCode, string $type = 'login'): bool
    {
        $otp = self::where('email', $email)
            ->where('otp', $otpCode)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otp) {
            return false;
        }

        // Mark as used
        $otp->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return true;
    }

    /**
     * Check if OTP is valid.
     */
    public function isValid(): bool
    {
        return ! $this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Check if OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope a query to only include valid OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope a query to only include expired OTPs.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope a query to only include used OTPs.
     */
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }
}
