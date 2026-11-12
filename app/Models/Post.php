<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'image',
        'views',
        'likes_count',
        'comments_count',
        'status',
        'published_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer'
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for the post.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the post is liked by a specific user.
     */
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope a query to only include popular posts.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include trending posts (last 7 days).
     */
    public function scopeTrending($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc');
    }

    /**
     * Get the post's image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        
        return asset('storage/' . $this->image);
    }

    /**
     * Get a short excerpt of the post content.
     */
    public function excerpt(int $length = 150): string
    {
        $content = strip_tags($this->content);
        
        if (Str::length($content) <= $length) {
            return $content;
        }
        
        return Str::limit($content, $length) . '...';
    }

    /**
     * Get the estimated reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        
        return max(1, $minutes); // Minimum 1 minute
    }

    /**
     * Get the formatted created date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedPublishedDateAttribute(): ?string
    {
        if (!$this->published_at) {
            return null;
        }
        
        return $this->published_at->format('M d, Y');
    }

    /**
     * Increment the post's views.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Increment the post's likes count.
     */
    public function incrementLikesCount(): void
    {
        $this->increment('likes_count');
    }

    /**
     * Decrement the post's likes count.
     */
    public function decrementLikesCount(): void
    {
        $this->decrement('likes_count');
    }

    /**
     * Increment the post's comments count.
     */
    public function incrementCommentsCount(): void
    {
        $this->increment('comments_count');
    }

    /**
     * Decrement the post's comments count.
     */
    public function decrementCommentsCount(): void
    {
        $this->decrement('comments_count');
    }

    /**
     * Check if the post is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' || $this->published_at <= now();
    }

    /**
     * Check if the post is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default values
        static::creating(function ($post) {
            if (!$post->user_id && auth()->check()) {
                $post->user_id = auth()->id();
            }
            
            if (!$post->status) {
                $post->status = 'published';
            }
            
            if (!$post->published_at) {
                $post->published_at = now();
            }
        });

        // When deleting a post, delete related likes and comments
        static::deleting(function ($post) {
            $post->likes()->delete();
            $post->comments()->delete();
        });
    }
}