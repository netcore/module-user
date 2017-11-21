<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Models\Setting;
use Nwidart\Modules\Facades\Module;
use Schema;

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
        $tableName = app($userModel)->getTable();

        $nameColumn = Schema::hasColumn($tableName, 'name') ? 'name' : 'first_name';

        $data = [
            'email'     => 'admin@admin.com',
            $nameColumn => 'Test Admin',
            'password'  => 'admin',
            'is_admin'  => 1,
        ];

        $user = $userModel::firstOrCreate(array_only($data, 'email'), $data);

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
                                'name'  => ucfirst($provider) . ' ' . str_replace('_', ' ', $key),
                                'type'  => 'text',
                            ],
                        ];
                    }

                }
            }
        }

        foreach ($settings as $data) {
            $setting = Setting::updateOrCreate($data[0], $data[1]);

            $translations = [];
            foreach (\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'value' => '',
                ];
            }
            $setting->storeTranslations($translations);
        }

        cache()->flush();
    }
}
