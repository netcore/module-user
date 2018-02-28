<?php

namespace Modules\User\Traits;

trait ModuleUser
{

    /**
     * Set "fullName" attribute on model
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name ?: $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Return the gravatar link for the users email
     *
     * @param  int $size
     * @return string
     */
    public function gravatar($size = 90)
    {
        return '//www.gravatar.com/avatar/' . md5($this->email) . '?s=' . $size . '&d=mm';
    }

}
