# phpspec Laravel Extension

[![Build Status](https://travis-ci.org/BenConstable/phpspec-laravel.png?branch=master)](https://travis-ci.org/BenConstable/phpspec-laravel)
[![Latest Stable Version](https://poser.pugx.org/benconstable/phpspec-laravel/v/stable.png)](https://packagist.org/packages/benconstable/phpspec-laravel)
[![Total Downloads](https://poser.pugx.org/benconstable/phpspec-laravel/downloads.png)](https://packagist.org/packages/benconstable/phpspec-laravel)

[phpspec](http://www.phpspec.net/) Extension for testing [Laravel](http://laravel.com/)
applications.

## Laravel 4

phpspec-laravel development is now targeted at Laravel 5. For use with Laravel
4, please install the latest `1.x` release.

## Why this extension?

This extension provides you with a bootstrapped Laravel environment when writing
your phpspec tests.

In detail,

**it:**

* Bootstraps the Laravel environment, so that you can use class aliases across
your application without running into to testing trouble, and so that you can
make use of Laravel's environment configuration for your testing environment.
You will also continue to be able to use Laravel's helper functions across your
codebase
* Allows you to test your Eloquent models, which I ran into difficulty with
before writing this extension
* Provides a few extra Laravel-specific phpspec matchers to make testing your
application code more straightforward

and

**it is not:**

* A swap-in replacement for Laravel's built in PHPUnit setup. If you'd like
integration and/or functional tests, please use that, [Behat](http://behat.org/),
or [Codeception](http://codeception.com/)

## Installation

Add this to your `composer.json`:

```json
{
    "require-dev": {
        "benconstable/phpspec-laravel": "~2.0@dev"
    }
}
```

then add this to your `phpspec.yml`:

```yaml
extensions:
    - PhpSpec\Laravel\Extension\LaravelExtension
```

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

### Test as normal

If you're not using any code specific to the Laravel environment, then you don't
need to do anything differently. Just write your phpspec tests as normal!

### A header

If you want to take advantage of Laravel's aliases, or use some of
[helper functions](http://laravel.com/docs/5.0/helpers), extend your specs
from `PhpSpec\Laravel\LaravelObjectBehavior`. This will stop the errors you'll
typically see.

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

### Testing Eloquent models

You should test your Eloquent models by extending the `PhpSpec\Laravel\EloquentModelBehavior`
class:

```php
<?php
namespace spec;

use PhpSpec\Laravel\EloquentModelBehavior;

class MyPostModelSpec extends EloquentModelBehavior {

    public function it_should_have_comments()
    {
        $this->comments()->shouldDefineRelationship('hasMany', 'Comment');
    }
}
```

### Accessing the IoC container

You shouldn't need to, but just in case, the booted Laravel IoC container can
be accessed like:

```php
<?php

$this->laravel->app['variable'];
```

in your specs.

## Custom Matchers

Some custom matchers are provided for convenience, feel free to ignore them
completely!

### DefineRelationshipMatcher

This matcher lets you check for the existence of a valid Eloquent relationship.

#### Usage

`should[Not]DefineRelationship('relationshipType', 'Related\Class')`

**Example**

```php
<?php
namespace spec;

use PhpSpec\Laravel\EloquentModelBehavior;

class MyPostModelSpec extends EloquentModelBehavior {

    public function it_should_have_comments()
    {
        $this->comments()->shouldDefineRelationship('hasMany', 'Comment');
    }
}
```

## Further reading

The following articles/websites have been useful to me when developing this
extension:

* [This issue](https://github.com/phpspec/phpspec/issues/299) on the phpspec
repo was a good help and is an interesting read
* Taylor Otwell's [video](http://taylorotwell.com/full-ioc-unit-testing-with-laravel/)
on DI and unit testing in Laravel was helpful in understanding the best way to
use phpspec
* [Laracasts](https://laracasts.com/) has some great posts and guides on phpspec and Laravel

## Thanks

* Thanks to [@obrignoni](https://github.com/obrignoni) for his great work in
getting this extension working with Laravel 5
