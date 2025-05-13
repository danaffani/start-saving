<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_group_id',
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the category group that the category belongs to.
     */
    public function category_group()
    {
        return $this->belongsTo(CategoryGroup::class);
    }

    /**
     * Get the user that the category belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}