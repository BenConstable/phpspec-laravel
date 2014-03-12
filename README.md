#PhpSpec Laravel Extension

**Work-in-progress** [PhpSpec](http://www.phpspec.net/) Extension for testing
[Laravel](http://laravel.com/) applications.

##What does this Extension provide?

* Bootstrapping of the Laravel environment
* Workarounds for testing your Eloquent models
* Extra Laravel-specific matchers

**It is not:**

* A swap-in replacement for Laravel's built in PHPUnit setup. If you'd like
functional or integration tests, please use that, [Behat](http://behat.org/),
or [Codeception](http://codeception.com/)

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

###General unit testing

You can unit test your classes like:

```php
<?php namespace spec;

use PhpSpec\Laravel\LaravelObjectBehavior;

class MyClassSpec extends LaravelObjectBehavior {

    // Test code here...
}
```

You can access the Laravel IoC container using:

```php
<?php

$this->laravel->app['variable'];
```

in any of your examples if needed.

###Testing models

You can test your models like:

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

And you can again access the Laravel IoC container using:

```php
<?php

$this->laravel->app['variable'];
```

in any of your examples.

##Custom Matchers

###DefineRelationshipMatcher

This matcher lets you check for the existence of a valid Eloquent relationship.

####Usage

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
* More matchers
* Improved environment Bootstrapping
* Improved documentation
* Tests

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

