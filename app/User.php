<?php

namespace App;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


class User extends Model implements Authenticatable, CanResetPassword, JWTSubject
{
    use AuthenticableTrait, HasFactory, Notifiable, CanResetPasswordTrait;

    //public $incrementing = false; // Necessary to make uuid works.

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'uuid', 'name', 'first_name', 'last_name', 'institution', 'email', 'password', 'email_token', 'verified', 'role', 'photo', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function corpus()
    {
        return $this->hasMany(Corpus::class);
    }

    public function verified()
    {
        $this->verified = 1;
        $this->save();
    }

    /**
     * Identifier stored in the "sub" claim of the JWT (the user id, as with tymon/jwt-auth 0.5).
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Same date format as Laravel 5.3 when serializing to JSON ("Y-m-d H:i:s").
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }
}
