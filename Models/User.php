<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    //@TODO: vai vispār vajag custom modeli veidot tā vietā, lai izmantotu traits un papildinātu default user modeli
    use \Modules\User\Traits\UserPermissions;

    //@TODO: varbūt šis jāiekļauj Admin modulī
    use \Modules\Crud\Traits\CrudifyModel;

    private static $crud = [
        /*'create'   => [
            'first_name' => 'text[required]',
            'last_name'  => 'textarea[required]',
            'email'      => 'email[required|email]',
            'password'   => 'password[confirmed]',
            'test1'      => '[required]',
            'test2'      => 'text'
        ]*/
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
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
    ];

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
    public function setNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
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
