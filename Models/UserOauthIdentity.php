<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOauthIdentity extends Model
{
    /**
     * Associated user model
     *
     * @var string
     */
    protected $userModel;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'user_oauth_identities';

    /**
     * Mass assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'provider_id',
        'profile_url',
        'user_id',
        'last_login_at'
    ];

    /**
     * Enable timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Date casts
     *
     * @var array
     */
    public $dates = [
        'last_login_at'
    ];

    /**
     * UserOauthIdentity constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->userModel = config('auth.providers.users.model');
    }

    /**
     * Identity belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo($this->userModel);
    }

    /**
     * Register last login time
     */
    public function setLastLoginTime()
    {
        $this->update([
            'last_login_at' => Carbon::now()
        ]);
    }
}