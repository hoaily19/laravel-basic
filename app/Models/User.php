<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
        'reset_token',
        'reset_token_expires_at',
        'oauth_provider',
        'oauth_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function generateResetToken()
    {
        $this->reset_token = sprintf("%06d", mt_rand(100000, 999999));
        $this->reset_token_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->reset_token;
    }

    public function verifyResetToken($token)
    {
        if (!$this->reset_token) {
            return false;
        }

        if ($this->reset_token !== $token) {
            return false;
        }

        $isValid = $this->reset_token_expires_at && 
                $this->reset_token_expires_at > now();

        if ($isValid) {
            return true;
        }

        return false;
    }

    public function clearResetToken()
    {
        $this->reset_token = null;
        $this->reset_token_expires_at = null;
        $this->save();
    }

    public function sendPasswordResetEmail()
    {
        $this->clearResetToken();
        $otp = $this->generateResetToken();

        Mail::send('emails.password-reset', ['otp' => $otp], function($message) {
            $message->to($this->email)
                    ->subject('Mã OTP Đặt Lại Mật Khẩu');
        });
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id')->withTimestamps();
    }
}
