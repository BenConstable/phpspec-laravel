<?php

namespace spec\PhpSpec\Laravel;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Laravel\Util\Laravel;

class LaravelObjectBehaviorSpec extends ObjectBehavior
{
    function it_is_a_laravel_behaviour()
    {
        $this->shouldImplement('PhpSpec\Laravel\LaravelBehaviorInterface');
        $this->shouldBeAnInstanceOf('PhpSpec\ObjectBehavior');
    }

    function it_accepts_a_laravel_utility(Laravel $laravel)
    {
        $this->setLaravel($laravel)->shouldHaveType('PhpSpec\Laravel\LaravelObjectBehavior');
    }

    function it_accepts_a_presenter(PresenterInterface $presenter)
    {
        $this->setPresenter($presenter)->shouldHaveType('PhpSpec\Laravel\LaravelObjectBehavior');
    }
}
