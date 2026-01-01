<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'fName',
        'lName',
        'email',
        'mobNumber',
        'password',
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

    /**
     * Get the posts for the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments for the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes for the user.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Check if user has liked a post.
     */
    public function hasLiked(Post $post): bool
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        // You can customize this based on your user roles
        // For example, check if email contains 'admin' or has specific role
        return str_contains($this->email, 'admin') || $this->id === 1;
    }

    /**
     * Get user's full name.
     */
    public function getNameAttribute(): string
    {
        return $this->fName . ' ' . $this->lName;
    }

    /**
     * Get user's post count.
     */
    public function getPostCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Get user's comment count.
     */
    public function getCommentCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Get user's like count.
     */
    public function getLikeCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Get user's initial for avatar.
     */
    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->fName, 0, 1) . substr($this->lName, 0, 1));
    }

    /**
     * Get user's first initial for avatar.
     */
    public function getFirstInitialAttribute(): string
    {
        return strtoupper(substr($this->fName, 0, 1));
    }
}