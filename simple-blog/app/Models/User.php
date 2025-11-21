<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'name',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'coins',
        'monetization_enabled',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEditor()
    {
        return $this->role === 'editor';
    }

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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_user');
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function monetizationApplications()
    {
        return $this->hasMany(MonetizationApplication::class);
    }

    /**
     * The users that follow this user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * The users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Check if user is following another user.
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow another user.
     */
    public function follow(User $user)
    {
        if ($this->id === $user->id) {
            return;
        }
        
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Unfollow another user.
     */
    public function unfollow(User $user)
    {
        $this->following()->detach($user->id);
    }

    /**
     * The posts that the user has bookmarked.
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }

    public function loves()
    {
        return $this->belongsToMany(Post::class, 'loves')->withTimestamps();
    }

    public function canAccessMenu($menuLabel)
    {
        // Admin has access to everything by default? Or specific menus?
        // Let's say admin role is superuser for now, OR we strictly follow menu assignment.
        // The requirement is "diatur hak akses menunya", so strict assignment is better.
        // But we should probably allow 'Dashboard' to everyone.
        
        if ($menuLabel === 'Dashboard') {
            return true;
        }

        return $this->menus()->where('label', $menuLabel)->exists();
    }
}
