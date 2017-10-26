<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Models\Setting;
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
        $settings = [];
        if (config('netcore.module-user.socialite')) {
            $providers = config('netcore.module-user.socialite-providers');
            $keys = ['_client_id', '_client_secret'];

            foreach ($providers as $provider => $state) {
                if ($state) {
                    foreach ($keys as $key) {
                        $settings[] = [
                            [
                                'group' => 'oauth',
                                'key'   => $provider . $key,
                            ],
                            [
                                'group' => 'oauth',
                                'key'   => $provider . $key,
                                'name'  => ucfirst($provider) . ' ' . str_replace('_', '', $key),
                                'value' => '',
                                'type'  => 'text', // Available types: text, select, checkbox, file
                            ]
                        ];
                    }

                }
            }
        }

        foreach ($settings as $setting) {
            Setting::updateOrCreate($setting[0], $setting[1]);
        }

        cache()->flush();
    }
}
