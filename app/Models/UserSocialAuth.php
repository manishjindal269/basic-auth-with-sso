<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialAuth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'provider_type',
        'provider_user_id',
        'user_id',
    ];

    /**
     * Get the user that owns the social authentication.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
