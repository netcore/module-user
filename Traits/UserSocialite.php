<?php

namespace Modules\User\Traits;

use Modules\User\Models\UserOauthIdentity;

trait UserSocialite
{
    /**
     * User has many identities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthIdentities()
    {
        return $this->hasMany(UserOauthIdentity::class);
    }
}