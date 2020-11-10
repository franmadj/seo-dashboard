<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function settings(){
        return $this->hasMany(Setting::class);
    }
    
    public function imapAccounts(){
        return $this->hasMany(ImapAccount::class);
    }
    
    public function templates(){
        return $this->hasMany(Template::class);
    }
    
    public function campaigns(){
        return $this->hasMany(Campaign::class);
    }
}
