<?php

namespace spec\PhpSpec\Laravel\Listener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Event\SpecificationEvent;

class LaravelListenerSpec extends ObjectBehavior
{
    function let(Laravel $laravel)
    {
        $this->beConstructedWith($laravel);
    }

    function it_is_a_listener()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_refreshes_the_laravel_framework_before_spec_is_run(Laravel $laravel, SpecificationEvent $event)
    {
        $laravel->refreshApplication()->shouldBeCalled();

        $this->beforeSpecification($event);
    }
}
