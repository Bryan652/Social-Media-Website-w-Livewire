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

    public function article() {
        return $this->hasMany(article::class);
    }

    /**
     * belongsToMany(RelatedModel, 'pivotTableName', 'columnName', 'columnName');
     *
     * 3rd parameter foreign key for the current model
     * 4th parameter foreign key for the related model
    */

    public function friends() {
        return $this->belongsToMany(User::class, 'friend_users', 'user_id', 'friend_id');
    }
    // return yung current user na innadd as friends

    public function friendsOf() {
        return $this->belongsToMany(User::class, 'friend_users', 'friend_id', 'user_id');
    }
    // retirn the user na nag add sa current user as friends

    public function allFriends() {
        return $this->friends->merge($this->friendsOf)->unique('id');
    }
    // combine both list na inadd ng user tsaka nag add sa currnet user
}
