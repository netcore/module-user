<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class UserDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(MenuTableSeeder::class);

        $userModel = config('auth.providers.users.model');
        $user = $userModel::firstOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'email'      => 'admin@admin.com',
                'first_name' => 'Test Admin',
                'password'   => 'admin',
                'is_admin'   => 1,
            ]);

        $module = Module::find('Permission');

        if ($module && $module->enabled()) {
            $user->role_id = 1;
            $user->save();
        }
    }
}
