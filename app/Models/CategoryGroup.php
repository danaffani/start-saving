<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryGroupFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the categories that belong to the category group.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the user that the category group belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
