<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @TODO: vajag uztaisīt smart-migrāciju, kas pielabo tabulas laukus, ja tiek izmantots defaultais
     * @TODO: name, nevis last_name un first_name;
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }


    /**
     * @param $name
     * @return mixed
     */
    public function hasRole($name)
    {
        if ( !$this->relationLoaded('roles') ) {
            $this->load('roles');
        }

        return $this->roles->contains('name', $name);
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->name ?: $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function getFirstnameAttribute()
    {
        return !empty( $this->first_name ) ? $this->first_name : $this->name;
    }

    /**
     * Return the gravatar link for the users email
     * @param  int $size
     * @return string
     */
    public function gravatar($size = 90)
    {
        return '//www.gravatar.com/avatar/' . md5($this->email) . '?s=' . $size . '&d=mm';
    }
}
