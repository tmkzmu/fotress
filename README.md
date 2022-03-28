# Fortress

This package provides an easy elegant way to create all necessary endpoints for registration and authorization API.
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

This command will publish all Action classes to `app\Actions\Fortress\Folder`. There are several actions:

* `Register`
* `Login`
* `EmailVerification`
* `PasswordReset`
* `UpdatePassword`
* `UpdateProfile`

All of them have their own default logic. Rest (endpoints, controllers, migrations) are provided by the package.

#### Run migrations

Execute ```php artisan migrate``` to run migrations.

## Configuration
Configuration file (`fortress.php`) is stored in `config` folder.

There are several configurations:
* `features` - an array of turned-on actions.
* `controller_class` - a default Controller handling all requests. If you want to use your own controller, set the class name here and implement the interface ```Tmkzmu\Fortress\Controllers\FortressControllerInterface```.
* `routes` - several configurations for routes:
  * `prefix` - define the prefix for routes (e.g. `api/v1`)
  * `middleware` - a middleware for all routes
  * `auth_middleware` - a middleware name to protect endpoints for authenticated users only
  * `login_throttle` - throttle requests for login endpoint
  * `reset_password_throttle` - throttle requests for password reset endpoint
  * `paths` - an endpoint paths for Actions
* `auth`
  * `verification_expire` - and the expiration time for mail verification URL
* `emails` - mail endpoints configuration
  * `callback_url` - an url for emails Actions
  * `email_verification_prefix` - a path for the email verification endpoint
  * `password_reset_prefix`- a path for the password reset endpoint


## License
MIT


