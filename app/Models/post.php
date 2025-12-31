<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image'
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     * (අනාගතයට - comments feature එකතු කරද්දි)
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Scope a query to only include popular posts.
     * (අනාගතයට - likes/views count අනුව)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    /**
     * Get the post's image URL.
     * If no image, return a placeholder.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'https://via.placeholder.com/800x400?text=No+Image';
    }

    /**
     * Get excerpt of the post content.
     */
    public function excerpt($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    /**
     * Check if the post belongs to the authenticated user.
     */
    public function isOwner()
    {
        if (auth()->check()) {
            return $this->user_id == auth()->id();
        }
        return false;
    }
}