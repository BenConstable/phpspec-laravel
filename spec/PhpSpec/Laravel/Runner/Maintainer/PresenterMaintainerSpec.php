<?php

namespace spec\PhpSpec\Laravel\Runner\Maintainer;

use ReflectionClass;
use ReflectionMethod;

use PhpSpec\ObjectBehavior;
use Prophecy\Prophet;
use Prophecy\Argument;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\SpecificationInterface;
use PhpSpec\Formatter\Presenter\PresenterInterface;

class PresenterMaintainerSpec extends ObjectBehavior
{
    private $refMethod;

    function let(PresenterInterface $presenter, ExampleNode $example, SpecificationInterface $context)
    {
        $this->beConstructedWith($presenter);

        $p = new Prophet;

        $this->refMethod = $p->prophesize('ReflectionMethod');
        $this->refMethod->invokeArgs(Argument::type('PhpSpec\SpecificationInterface'), Argument::type('array'))->shouldBeCalled();

        $refClass = $p->prophesize('ReflectionClass');

        $refClass->hasMethod('setPresenter')->willReturn(true);
        $refClass->hasMethod('setPresenter')->shouldBeCalled();

        $refClass->getMethod('setPresenter')->willReturn($this->refMethod->reveal());
        $refClass->getMethod('setPresenter')->shouldBeCalled();

        $specNode = $p->prophesize('PhpSpec\Loader\Node\SpecificationNode');
        $specNode->getClassReflection()->willReturn($refClass->reveal());

        $example->getSpecification()->willReturn($specNode->reveal());
    }

    function it_is_a_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Runner\Maintainer\MaintainerInterface');
    }

    function it_supports_objects_with_a_setPresenter_method(ExampleNode $example)
    {
        $this->supports($example)->shouldBe(true);
    }

    function it_sets_a_presenter_object_on_a_spec(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prepare($example, $context, $matchers, $collaborators);
    }

    function it_doesnt_tear_down_anything(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->teardown($example, $context, $matchers, $collaborators);
    }

    function it_has_a_high_priority()
    {
        $this->getPriority()->shouldBe(1000);
    }
}
