<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $table = 'users';
    // protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'email',
        'password',
        'token',
        'expiry',
        'link'
    ];

    public function messages()
    {
        return $this->hasMany('App\Models\Message','sender_id');
    }
    public function receivermessage()
    {
        return $this->hasMany('App\Models\Message','receiver_id');
    }
}
