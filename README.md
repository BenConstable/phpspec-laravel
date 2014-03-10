#PhpSpec Laravel Extension

Work-in-progress [PhpSpec](http://www.phpspec.net/) Extension for testing
[Laravel](http://laravel.com/) applications.

##Installation

Add this to your `composer.json`:

```json
{
    "require": {
        "benconstable/phpspec-laravel": "*"
    }
}
```

then add this to your `phpspec.yml`:

```yaml
extensions:
    - PhpSpec\Laravel\Extension\LaravelExtension
```

##Configuration

###Testing environment

By default, the extension bootstraps Laravel in the `testing` environment. You
can change this to production (or whatever you like) by setting:

```yaml
laravel_extension:
    testing_environment: 'production'
```

in your `phpspec.yml`.

##Usage

The base classes provided by the extension mimic most of the behaviour found at
[\Illuminate\Foundation\Testing\TestCase](https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Testing/TestCase.php), albeit with some differences.

###Unit testing

You can unit test your Laravel classes like:

```php
<?php namespace spec;

use PhpSpec\Laravel\LaravelObjectBehavior;

class MyModelSpec extends LaravelObjectBehavior {

    // Test code here...
}
```

You can access the Laravel IoC container using:

```php
<?php

$this->laravel->app['variable'];
```

in any of your examples.

###Testing routes

You can test your application routes like:

```php
<?php namespace spec;

use PhpSpec\Laravel\LaravelRouteBehavior;

class MyRouteSpec extends LaravelRouteBehavior {

    public function it_should_be_ok()
    {
        $result = $this->call('GET', '/');

        // Check response here...
    }
}
```

And you can again access the Laravel IoC container using:

```php
<?php

$this->laravel->app['variable'];
```

in any of your examples.
