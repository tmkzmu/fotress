# Fortress

![PHP from Packagist](https://img.shields.io/packagist/php-v/laravel/laravel?style=flat-square)
![Laravel version](https://img.shields.io/badge/Laravel->%3D8.0-green?style=flat-square)

This package provides an easy elegant way to create all necessary endpoints for registration and authorization API for Laravel applications.
* user registration
* login
* email verification
* updates
* password reset

## Installation

#### Install package from composer:

```composer require tmkzmu/fortress```

#### Publish Actions

```php artisan vendor:publish --provider="Tmkzmu\Fortress\FortressServiceProvider"```

This command will publish all Action classes to `app\Actions\Fortress` folder. There are several actions:

* `Register`
* `Login`
* `EmailVerification`
* `PasswordReset`
* `UpdatePassword`
* `UpdateProfile`

All of them have their own default logic. Rest (endpoints, controllers, migrations) are provided by the package.

#### Run migrations

Execute ```php artisan migrate``` to run migrations.

#### Add traits
If you would like to use email verification add `Tmkzmu\Fortress\Traits\VerifyEmailTrait` trait and Laravel `MustVerifyEmail` interface  to your `User` model.

## Configuration
Configuration file (`fortress.php`) is stored in `config` folder.

There are several configurations:
* `features` - an array of turned-on actions.
* `controller_class` - a default Controller handling all requests. If you want to use your own controller, set the class name here and implement the interface ```Tmkzmu\Fortress\Controllers\FortressControllerInterface```.
* `routes` - several configurations for routes:
  * `prefix` - define the prefix for routes (e.g. `api/v1`)
  * `middleware` - a middleware for all routes
  * `auth_middleware` - a middleware name to protect endpoints for authenticated users only
  * `throttle` - throttle requests for endpoints:
    * `login`
    * `email_verification`
    * `reset_password`
  * `paths` - an endpoint paths for Actions
* `auth`
  * `verification_expire` - the expiration time for mail verification URL
* `emails` - mail endpoints configuration
  * `callback_url` - an url for emails Actions
  * `email_verification_prefix` - a path for the email verification endpoint
  * `password_reset_prefix`- a path for the password reset endpoint

## Tests
All endpoint are tested. You can use package tests by e.g. extend your test by class `Tmkzmu\Fortress\Feature\FortressTest`

## License
MIT


