<?php

namespace spec\PhpSpec\Laravel\Matcher\Eloquent;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use PhpSpec\Formatter\Presenter\PresenterInterface;

class DefineRelationshipMatcherSpec extends ObjectBehavior
{
    function let(PresenterInterface $presenter)
    {
        $this->beConstructedWith($presenter);

        $presenter->presentString(Argument::type('string'))->willReturnArgument();
        $presenter->presentValue(Argument::any())->willReturn('val');
    }

    function it_is_a_basic_matcher()
    {
        $this->shouldHaveType('PhpSpec\Matcher\BasicMatcher');
    }

    function it_only_supports_define_relationship()
    {
        $this
            ->supports('defineRelationship', Argument::cetera(), array())
            ->shouldBe(true);

        $this
            ->supports('anythingElse', Argument::cetera(), array())
            ->shouldBe(false);
    }

    function it_matches_a_belongs_to_relationship()
    {
        $p = new Prophet;

        $related = $p->prophesize('PhpSpec\Laravel\Test\Example');

        $belongsTo = $p->prophesize('Illuminate\Database\Eloquent\Relations\BelongsTo');
        $belongsTo->getRelated()->willReturn($related->reveal());

        $this
            ->positiveMatch('name', $belongsTo->reveal(), array('belongsTo', 'PhpSpec\Laravel\Test\Example'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('positiveMatch', array('name', $belongsTo->reveal(), array('belongsTo', 'PhpSpec\Laravel\Test\OtherExample')));

        $this
            ->negativeMatch('name', $belongsTo->reveal(), array('belongsTo', 'PhpSpec\Laravel\Test\OtherExample'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('negativeMatch', array('name', $belongsTo->reveal(), array('belongsTo', 'PhpSpec\Laravel\Test\Example')));
    }

    function it_matches_a_has_many_relationship()
    {
        $p = new Prophet;

        $related = $p->prophesize('PhpSpec\Laravel\Test\Example');

        $belongsTo = $p->prophesize('Illuminate\Database\Eloquent\Relations\HasMany');
        $belongsTo->getRelated()->willReturn($related->reveal());

        $this
            ->positiveMatch('name', $belongsTo->reveal(), array('hasMany', 'PhpSpec\Laravel\Test\Example'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('positiveMatch', array('name', $belongsTo->reveal(), array('hasMany', 'PhpSpec\Laravel\Test\OtherExample')));

        $this
            ->negativeMatch('name', $belongsTo->reveal(), array('hasMany', 'PhpSpec\Laravel\Test\OtherExample'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('negativeMatch', array('name', $belongsTo->reveal(), array('hasMany', 'PhpSpec\Laravel\Test\Example')));
    }

    function it_matches_a_belongs_to_many_relationship()
    {
        $p = new Prophet;

        $related = $p->prophesize('PhpSpec\Laravel\Test\Example');

        $belongsTo = $p->prophesize('Illuminate\Database\Eloquent\Relations\BelongsToMany');
        $belongsTo->getRelated()->willReturn($related->reveal());

        $this
            ->positiveMatch('name', $belongsTo->reveal(), array('belongsToMany', 'PhpSpec\Laravel\Test\Example'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('positiveMatch', array('name', $belongsTo->reveal(), array('belongsToMany', 'PhpSpec\Laravel\Test\OtherExample')));

        $this
            ->negativeMatch('name', $belongsTo->reveal(), array('belongsToMany', 'PhpSpec\Laravel\Test\OtherExample'));

        $this
            ->shouldThrow('PhpSpec\Exception\Example\FailureException')
            ->during('negativeMatch', array('name', $belongsTo->reveal(), array('belongsToMany', 'PhpSpec\Laravel\Test\Example')));
    }
}

namespace PhpSpec\Laravel\Test;
class Example {}
class OtherExample {}
