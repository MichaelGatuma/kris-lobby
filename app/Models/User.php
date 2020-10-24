<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $Title
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $two_factor_secret
 * @property string $two_factor_recovery_codes
 * @property string $profPic
 * @property boolean $isAdmin
 * @property string $remember_token
 * @property integer $current_team_id
 * @property string $profile_photo_path
 * @property string $created_at
 * @property string $updated_at
 * @property string $verified_at
 * @property Post[] $posts
 * @property Publication[] $publications
 * @property Researcher[] $researchers
 * @property Researchproject[] $researchprojects
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications()
    {
        return $this->hasMany('App\Models\Publication', 'UserID');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchers()
    {
        return $this->hasMany('App\Models\Researcher', 'User_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchprojects()
    {
        return $this->hasMany('App\Models\Researchproject', 'User_ID');
    }
}
