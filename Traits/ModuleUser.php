<?php

namespace Modules\User\Traits;

trait ModuleUser {

    /**
     * Set "fullName" attribute on model
     *
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->name ?: $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Set "name" attribute on model
     *
     * @param $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
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