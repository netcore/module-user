# Easier user management.

Main idea of this package is to make user management more simple in CMS side. And also to provide simple socialite integration with additional traits and helpers.

## Pre-installation 
This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages installed:

https://github.com/netcore/netcore

https://github.com/netcore/module-admin

https://github.com/netcore/module-setting

https://github.com/nWidart/laravel-modules

## Instalation

```
composer require netcore/module-user
```

Publish config, assets, migrations. Migrate and seed:

```
php artisan module:publish-config User
php artisan module:publish User
php artisan module:publish-migration User
php artisan migrate
php artisan module:seed User
```

You need to add `UserPermissions` trait to your user model in order to be able to log in acp.

You should be good to go. 

## Features

- You get user management section in your acp where you can create, delete, edit, show users. Also you can configure what actions are allowed to be done in User section. For example you disable deleting users in acp. In order to do so you need to navigate to `config/netcore/module-user.php` config file and change from true to false corresponding variables:

```
...
'allow' => [
        'delete' => false,
        'create' => true,
        'view'   => false,
        'export' => false,
    ]
...
```

- Also if you want to use socialite you can configure providers in `config/netcore/module-user.php`, also turno on and off socialite support. If you use socialite you need to add `UserSocialite` trait to you User model and `ControllerSocialite` to your AuthController.

It will seed settings for oAuth and you will be able to configure them in Settings section in your page.

Dont forget to add [Socialite](https://github.com/laravel/socialite) package to your composer.json file 

In order to call socialite request you can use this url `/login/{provider}` where {provider} is replaced with facebook or google, depends on what you have choosen.

You need to add `providerCallback` method to your AuthController, in this method you will be able to manage data which you receive from provider.



```
public function providerCallback(string $provider)
{
    $this->providerGate($provider);
    try {
        $providerUser = Socialite::driver($provider)->user(); // Provider data
    } catch (Exception $exception) {
      return $this->handleBadProviderResponse($exception, $provider);
    }
}
        
```

### Exporting users

- First of all you need to configure export filters, add export data formatter to related model (if option with relation is required)
- At module-user.php config file

```php 

    'export_options' => [
        // Users only
        [
            'title'   => 'Users only',
            // Filters that can be appied to exportable data
            'filters' => [
                [
                    'name'     => 'Register date from:', // Filter name
                    'key'      => 'created_at_from', // Filter key (for html input - must be unique) 
                    'type'     => 'date', // Field type
                    'field'    => 'created_at', // Column name in database
                    'operator' => '>=', // SQL select operator
                    'required' => false, // Is required?
                ],
                [
                    'name'     => 'Register date to:',
                    'key'      => 'created_at_to',
                    'type'     => 'date',
                    'field'    => 'created_at',
                    'operator' => '<=',
                    'required' => false,
                ],
                [
                    'name'           => 'Pick:',
                    'key'            => 'is_active',
                    'type'           => 'select',
                    'field'          => 'is_active',
                    'operator'       => '=',
                    'required'       => false,
                    'select_options' => [
                        null => 'Both',
                        0    => 'Inactive users',
                        1    => 'Active users',
                    ],
                ],
            ],
        ],
        // User with some relation/-s
        [
            'title'        => 'User with classifieds',
            'find_by'      => 'id:fullName', // format - column:userRepresentableData
            'withRelation' => [
                'name'    => 'classifieds', // relation name
                // Relation filters
                'filters' => [
                    [
                        'name'           => 'Of type:',
                        'key'            => 'of_type',
                        'type'           => 'select',
                        'field'          => 'type',
                        'operator'       => '=',
                        'required'       => false,
                        'select_options' => [
                            null   => 'All',
                            'buy'  => 'Buy',
                            'sell' => 'Sell',
                            'rent' => 'Rent',
                        ],
                    ],
                    [
                        'name'           => 'From date:',
                        'key'            => 'created_at_from',
                        'type'           => 'date',
                        'field'          => 'created_at',
                        'operator'       => '>=',
                        'required'       => false,
                    ],
                    [
                        'name'           => 'To date:',
                        'key'            => 'created_at_to',
                        'type'           => 'date',
                        'field'          => 'created_at',
                        'operator'       => '<=',
                        'required'       => false,
                    ],
                ],
            ],
        ],
    ],
```

And then you need to implement following method in your related model
```php 
    /**
     * Export config
     *
     * @return array
     */
    public function getExportableFieldsMap(): array
    {
        return [
            // Column name => Value
            'City' => $this->city->name ?? null,
            'Language' => $this->language_iso_code,
            'Type' => $this->type,
            'Name' => $this->name,
            'Price' => $this->price,
            ...
        ];
    }
```

### Datatable configuration

- By default columns scanned by CRUD module will be shown. 
- You can configure necessary columns, modify titles and modify values as well.

- Create presenter somewhere, for example in app/Presenters/AdminUsersDatatablePresenter.php
- Example file:
```php 
    <?php
    
    namespace App\Presenters;
    
    class AdminUsersDatatablePresenter
    {
        /**
         * Columns to show.
         *
         * @var array
         */
        public $showColumns = [
            'created_at'        => 'Registerted at',
            'is_active'         => 'Is active?',
            'name'              => 'Name',
            'email'             => 'E-mail address',
            'phone'             => 'Phone',
            'language_iso_code' => [
                'title' => 'Country', // column title
                'name'  => 'language.title', // for query
                'data'  => 'language.title', // for display data
            ],
        ];
    
        /** ---------- Modifiers ---------- */
    
        /**
         * is_active column modifier.
         *
         * @param $user
         * @return string
         */
        public function isActive($user): string
        {
            return $user->is_active ? 'Yes' : 'No';
        }
        
        /**
         * created_at column modifier.
         * 
         * @param $user
         * @return string
         */
        public function createdAt($user): string 
        {
            return $user->created_at->format('d.m.Y');
        }
    }
```