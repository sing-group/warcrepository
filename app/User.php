<?php

namespace App;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Notifications\Notifiable;


class User extends Model implements Authenticatable, CanResetPassword
{
    use AuthenticableTrait, Notifiable, CanResetPasswordTrait;

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


}