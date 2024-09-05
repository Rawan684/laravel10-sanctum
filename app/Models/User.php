<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Events\VerificationCodeGenerated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Notifications\EmailVerificationNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $incrementing = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'profile_photo',
        'certificate',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    public function generateTwoFactorCode()
    {
        $code = Str::random(6, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
        $this->two_factor_code = $code;
        $this->two_factor_expires_at = Carbon::now()->addMinutes(10);
        $this->save();
        return $code;

        // Fire the VerificationCodeGenerated event
        event(new VerificationCodeGenerated($this->user, $code));
    }

    public function confirmTwoFactorCode($code)
    {
        if ($this->two_factor_code  === $code) {
            $this->email_verified_at = now();
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
