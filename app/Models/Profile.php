<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'user_id',
        'about_myself',
        'work'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
