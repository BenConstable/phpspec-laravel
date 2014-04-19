<?php

namespace spec\PhpSpec\Laravel;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Formatter\Presenter\PresenterInterface;

class EloquentModelBehaviorSpec extends ObjectBehavior
{
    function it_is_a_laravel_object_behavior()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Laravel\LaravelObjectBehavior');
    }
}
