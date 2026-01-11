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

    /**
 * Get the users that this user follows
 */
public function followings()
{
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
                ->withTimestamps();
}

/**
 * Get the users who follow this user
 */
public function followers()
{
    return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
                ->withTimestamps();
}

/**
 * Check if user follows another user
 */
public function isFollowing(User $user): bool
{
    return $this->followings()->where('following_id', $user->id)->exists();
}

/**
 * Check if user is followed by another user
 */
public function isFollowedBy(User $user): bool
{
    return $this->followers()->where('follower_id', $user->id)->exists();
}

/**
 * Follow a user
 */
public function follow(User $user): void
{
    if (!$this->isFollowing($user) && $this->id !== $user->id) {
        $this->followings()->attach($user->id);
    }
}

/**
 * Unfollow a user
 */
public function unfollow(User $user): void
{
    $this->followings()->detach($user->id);
}

/**
 * Toggle follow status
 */
public function toggleFollow(User $user): bool
{
    if ($this->isFollowing($user)) {
        $this->unfollow($user);
        return false;
    } else {
        $this->follow($user);
        return true;
    }
}

/**
 * Get followers count
 */
public function getFollowersCountAttribute(): int
{
    return $this->followers()->count();
}

/**
 * Get following count
 */
public function getFollowingCountAttribute(): int
{
    return $this->followings()->count();
}

/**
 * Get the user's avatar URL
 */
public function getAvatarUrlAttribute(): string
{
    if ($this->avatar) {
        return asset('storage/' . $this->avatar);
    }
    
    if ($this->profile_pic) {
        return asset('storage/' . $this->profile_pic);
    }
    
    // Generate gravatar or default avatar
    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
}

/**
 * Get the user's small avatar URL
 */
public function getSmallAvatarUrlAttribute(): string
{
    if ($this->avatar) {
        return asset('storage/' . $this->avatar);
    }
    
    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=60";
}

/**
 * Get the user's medium avatar URL
 */
public function getMediumAvatarUrlAttribute(): string
{
    if ($this->avatar) {
        return asset('storage/' . $this->avatar);
    }
    
    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=120";
}
}