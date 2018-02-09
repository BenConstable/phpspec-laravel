<?php

namespace PhpSpec\Laravel\Runner\Maintainer;

use PhpSpec\Specification;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\Maintainer;
use PhpSpec\Formatter\Presenter\Presenter;

/**
 * This maintainer is used to bind the app Presenter to behaviours.
 */
class PresenterMaintainer implements Maintainer
{
    /**
     * @var \PhpSpec\Formatter\Presenter\Presenter
     */
    private $presenter;

    /**
     * Constructor.
     *
     * @param \PhpSpec\Formatter\Presenter\Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Check if this maintainer applies to the given node.
     *
     * Will check for the `setPresenter` method.
     *
     * @param \PhpSpec\Loader\Node\ExampleNode $example
     * @return boolean
     */
    public function supports(ExampleNode $example):bool
    {
        return $example->getSpecification()->getClassReflection()->hasMethod('setPresenter');
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {
        $reflection = $example->getSpecification()->getClassReflection()->getMethod('setPresenter');
        $reflection->invokeArgs($context, [$this->presenter]);
    }

    /**
     * {@inheritdoc}
     */
    public function teardown(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {
    }

    /**
     * Give this maintainer a high priority in the stack.
     *
     * @return int
     */
    public function getPriority():int
    {
        return 1000;
    }
}
