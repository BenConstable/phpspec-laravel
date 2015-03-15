# phpspec Laravel Extension

[![Build Status](https://travis-ci.org/BenConstable/phpspec-laravel.png?branch=master)](https://travis-ci.org/BenConstable/phpspec-laravel)
[![Latest Stable Version](https://poser.pugx.org/benconstable/phpspec-laravel/v/stable.png)](https://packagist.org/packages/benconstable/phpspec-laravel)
[![Total Downloads](https://poser.pugx.org/benconstable/phpspec-laravel/downloads.png)](https://packagist.org/packages/benconstable/phpspec-laravel)
[![License](https://poser.pugx.org/benconstable/phpspec-laravel/license.svg)](https://packagist.org/packages/benconstable/phpspec-laravel)

[phpspec](http://www.phpspec.net/) Extension for testing [Laravel](http://laravel.com/)
applications.

## Installation

Add this to your `composer.json`:

```json
{
    "require-dev": {
        "benconstable/phpspec-laravel": "~2.0"
    }
}
```

then add this to your `phpspec.yml`:

```yaml
extensions:
    - PhpSpec\Laravel\Extension\LaravelExtension
```

You can take a look at [`example.phpspec.yml`](https://github.com/BenConstable/phpspec-laravel/blob/master/example.phpspec.yml) for a good set of sensible phpspec defaults for a Laravel project.

#### Laravel 4

phpspec-laravel development is now targeted at Laravel 5. For use with Laravel
4, please install the latest `1.x` release.

## Why this extension?

This extension provides you with a bootstrapped Laravel environment when writing
your phpspec tests.

It allows you to make use of some of the nice features that Laravel provides, like
[class aliases](https://github.com/laravel/laravel/blob/master/config/app.php#L161)
and [helper functions](http://laravel.com/docs/5.0/helpers), without being
hindered by your testing framework.

This extension **is not** a swap-in replacement for Laravel's built in PHPUnit setup.
If you'd like integration and/or functional tests, please use that,
[Behat](http://behat.org/), or [Codeception](http://codeception.com/).

#### A note on the database and Eloquent

This extension previously provided the ability to migrate and seed the database,
and also provided functionality to make testing Eloquent models easier.

With version 2.0 (for Laravel 5), the database functionality has been removed.
Testing the database layer is beyond the scope of phpspec, and so in order to
encourage best practices these database commands have been removed. You should use
PHPUnit or similar to run integration tests on your database.

Unfortunately, with Laravel 5 the previous functionality that made
[testing Eloquent models](https://github.com/BenConstable/phpspec-laravel/tree/laravel-4#testing-eloquent-models) easier and provided a [custom matcher for testing relationships](https://github.com/BenConstable/phpspec-laravel/tree/laravel-4#custom-matchers) has become much more difficult to implement
without hitting the database. I've had to remove it for now, but I will try to
add it in again at a later date. Regardless, you should try to keep business
logic out of models and test your database layer using integration tests.

## Configuration

### Testing environment

By default, the extension bootstraps Laravel in the `testing` environment. You
can change this to production (or whatever you like) by setting:

```yaml
laravel_extension:
    testing_environment: 'production'
```

in your `phpspec.yml`.

### App bootstrap path

By default, the extension will bootstrap your app by looking for `bootstrap/app.php`
in the directory above `vendor/`. This is the default location that Laravel
provides.

You can manually specify the path to the bootstrap file if you're using a non-standard
installation, like so:

```yaml
laravel_extension:
    framework_path: "/non/standard/laravel/setup/app.php"
```

You can specify either an absolute path (use leading slash), or a path relative
to the `vendor/` directory.

## Usage

### Testing without Laravel

If you're not using any code specific to the Laravel environment, then you don't
need to do anything differently. Just write your phpspec tests as normal!

### Testing with Laravel

If you want to take advantage of Laravel's aliases, or use some of its
[helper functions](http://laravel.com/docs/5.0/helpers), extend your specs
from `PhpSpec\Laravel\LaravelObjectBehavior`. This will prevent errors when
testing.

**For example, this class uses an alias:**

```php
<?php
namespace App;

use Inspiring;

class MyInspiring extends Inspiring
{
    public function quoteBackwards()
    {
        return strrev(parent::quote());
    }
}
```

and without extending from `PhpSpec\Laravel\LaravelObjectBehavior`:

```php
<?php
namespace spec\App;

use PhpSpec\ObjectBehavior;

class MyInspiringSpec extends ObjectBehavior
{
    function it_inspires_backwards()
    {
        $this->quoteBackwards()->shouldBeString();
    }
}
```

you'll get `Fatal error: Class 'Inspiring' not found...`. But extending from `PhpSpec\Laravel\LaravelObjectBehavior`:

```php
<?php
namespace spec\App;

use PhpSpec\Laravel\LaravelObjectBehavior;

class MyInspiringSpec extends LaravelObjectBehavior
{
    function it_inspires_backwards()
    {
        $this->quoteBackwards()->shouldBeString();
    }
}
```

you'll get `✔ inspires backwards`.

**and this class uses a helper function:**

```php
<?php
namespace App;

class MyEncryptor
{
    public function encrypt($arg)
    {
        return bcrypt($arg);
    }
}
```

and without extending from `PhpSpec\Laravel\LaravelObjectBehavior`:

```php
<?php
namespace spec\App;

use PhpSpec\ObjectBehavior;

class MyEncryptor extends ObjectBehavior
{
    function it_encrypts_a_string()
    {
        $this->encrypt()->shouldBeString();
    }
}
```

you'll get `Fatal error: Call to a member function make() on a non-object...`.
But extending from `PhpSpec\Laravel\LaravelObjectBehavior`:

```php
<?php
namespace spec\App;

use PhpSpec\Laravel\LaravelObjectBehavior;

class MyEncryptor extends LaravelObjectBehavior
{
    function it_encrypts_a_string()
    {
        $this->encrypt()->shouldBeString();
    }
}
```

you'll get `✔ encrypts a string`.

### Accessing the IoC container

If you need to access the [Service Container](http://laravel.com/docs/5.0/container)
in your specs, just use the `app()` helper!

## Learning more about phpspec and Laravel

[Laracasts](https://laracasts.com/) has some great guides on phpspec and Laravel.
['Laravel, phpspec and refactoring'](https://laracasts.com/lessons/phpspec-laravel-and-refactoring)
is a good starting point; it shows how you should use phpspec with Laravel,
and covers the basics of writing tests (and it's free!).

## Thanks

Thanks to [@obrignoni](https://github.com/obrignoni) for his great work in
getting this extension working with Laravel 5, to all of the [other contributors](https://github.com/BenConstable/phpspec-laravel/graphs/contributors) and to everyone who's reported
issues and bugs with the project.
