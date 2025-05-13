<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saving extends Model
{
    /** @use HasFactory<\Database\Factories\SavingFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'saving_target_id',
        'amount',
    ];

    /**
     * Get the user that owns the saving.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the saving target that owns the saving.
     */
    public function savingTarget(): BelongsTo
    {
        return $this->belongsTo(SavingTarget::class);
    }
}
