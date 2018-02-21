<?php

namespace Modules\User\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function guests_cannot_view_users()
    {
        $this->get(route('user::users.index'))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_view_users()
    {
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($user);

        $this->get(route('user::users.index'))->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_edit_users()
    {
        $user = app(config('netcore.module-admin.user.model'))->first();

        $this->get(route('user::users.edit', $user))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_edit_users()
    {
        $user = app(config('netcore.module-admin.user.model'))->first();
        $admin = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($admin);

        $this->get(route('user::users.edit', $user))->assertStatus(200);
    }
}
