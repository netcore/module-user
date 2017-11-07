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


