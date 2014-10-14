#PhpSpec Laravel Extension

[![Build Status](https://travis-ci.org/BenConstable/phpspec-laravel.png?branch=master)](https://travis-ci.org/BenConstable/phpspec-laravel)
[![Latest Stable Version](https://poser.pugx.org/benconstable/phpspec-laravel/v/stable.png)](https://packagist.org/packages/benconstable/phpspec-laravel)
[![Total Downloads](https://poser.pugx.org/benconstable/phpspec-laravel/downloads.png)](https://packagist.org/packages/benconstable/phpspec-laravel)

[PhpSpec](http://www.phpspec.net/) Extension for testing [Laravel](http://laravel.com/)
applications.

##Why this extension?

This extension allows you to test your objects and classes as you would normally
with PhpSpec, but gives you a Laravel application context to test within, so
that you can continue to make use of Laravel's nice features.

In detail,

**it:**

* Bootstraps the Laravel environment, so that you can use class aliases across
your application without running into to testing trouble, and so that you can
make use of Laravel's environment configuration for your testing environment.
You will also continue to be able to use Laravel's helper functions across your
codebase
* Allows you to test your Eloquent models, which I ran into difficulty with
before writing this extension
* Provides a few extra Laravel-specific PhpSpec matchers to make testing your
application code more straightforward

and

**it is not:**

* A swap-in replacement for Laravel's built in PHPUnit setup. If you'd like
functional tests, please use that, [Behat](http://behat.org/),
or [Codeception](http://codeception.com/)

##Installation

Add this to your `composer.json`:

```json
{
    "require": {
        "benconstable/phpspec-laravel": "~1.0"
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

###Database migration

If you'd like your database migrations to be run before each spec, you can
specify:

```yaml
laravel_extension:
    migrate_db: true
```

This is useful if you'd like to make use of a SQLite in-memory database for any
Eloquent model tests (see [here](http://code.tutsplus.com/tutorials/testing-like-a-boss-in-laravel-models--net-30087)
for how you'd set this up).

####Seeding

If you're running migrations, you can also specify that you'd like your database
to be seeded:

```yaml
laravel_extension:
    migrate_db: true
    seed_db: true
    seed_class: 'DatabaseSeeder'
```

`seed_class` is optional, and defaults to `DatabaseSeeder`. If you are using a
custom seed class, be sure to add the fully qualified namespace (e.g
`My\Custom\Seeder`)

###Laravel path

By default, the extension will look for the Laravel framework files in the
directory above the `vendor/` dir, like so:

```
- app/
- bootstrap/
- public/
- vendor/
- phpspec.yml
```

This is the default layout of a Laravel project. However, you can manually
specify the path to the Laravel framework files like so:

```yaml
laravel_extension:
    framework_path: "/shared/laravel/install"
```

You can specify either an absolute path (use leading slash), or a path relative
to the `vendor/` directory. For example, a relative path setting for the default
install location would be as follows:

```yaml
laravel_extension:
    framework_path: ".." # Read like vendor/../
```

##Usage

###General testing

You should test your regular classes by extending the `PhpSpec\Laravel\LaravelObjectBehavior`
class:

```php
<?php namespace spec;

use PhpSpec\Laravel\LaravelObjectBehavior;

class MyClassSpec extends LaravelObjectBehavior {

    // Test code here...
}
```

###Testing Eloquent models

You should test your Eloquent models by extending the `PhpSpec\Laravel\EloquentModelBehavior`
class:

```php
<?php namespace spec;

use PhpSpec\Laravel\EloquentModelBehavior;

class MyPostModelSpec extends EloquentModelBehavior {

    public function it_should_have_comments()
    {
        $this->comments()->shouldDefineRelationship('hasMany', 'Comment');
    }
}
```

###Accessing the IoC container

You shouldn't need to, but just in case, the booted Laravel IoC container can
be accessed like:

```php
<?php

$this->laravel->app['variable'];
```

in your specs.

##Custom Matchers

Some custom matchers are provided for convenience, feel free to ignore them
completely!

###DefineRelationshipMatcher

This matcher lets you check for the existence of a valid Eloquent relationship.

####Usage

`should[Not]DefineRelationship('relationshipType', 'Related\Class')`

**Example**

```php
<?php namespace spec;

use PhpSpec\Laravel\EloquentModelBehavior;

class MyPostModelSpec extends EloquentModelBehavior {

    public function it_should_have_comments()
    {
        $this->comments()->shouldDefineRelationship('hasMany', 'Comment');
    }
}
```

##Roadmap

* Improved code generation for Laravel

##Further reading

The following articles/websites have been useful to me when developing this
extension:

* This [open issue](https://github.com/phpspec/phpspec/issues/299) on the PHPSpec
repo was a good help and is an interesting read
* Taylor Otwell's [video](http://taylorotwell.com/full-ioc-unit-testing-with-laravel/)
on DI and unit testing in Laravel
* [Laracasts](https://laracasts.com/) has a few posts and guides on PHPSpec and
Laravel
* [This tutorial](http://code.tutsplus.com/tutorials/testing-like-a-boss-in-laravel-models--net-30087) has some useful information on setting up your database
for testing

