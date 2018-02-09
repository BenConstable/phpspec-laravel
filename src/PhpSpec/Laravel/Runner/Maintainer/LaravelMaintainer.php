<?php

namespace PhpSpec\Laravel\Runner\Maintainer;

use PhpSpec\Specification;
use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\Maintainer;

/**
 * This maintainer is used to bind the Laravel wrapper to nodes that implement
 * the `setLaravel` method.
 */
class LaravelMaintainer implements Maintainer
{
    /**
     * Laravel wrapper.
     *
     * @var \PhpSpec\Laravel\Util\Laravel
     */
    private $laravel;

    /**
     * Constructor.
     *
     * @param \PhpSpec\Laravel\Util\Laravel $laravel
     */
    public function __construct(Laravel $laravel)
    {
        $this->laravel = $laravel;
    }

    /**
     * Check if this maintainer applies to the given node.
     *
     * Will check for the 'setLaravel' method.
     *
     * @param \PhpSpec\Loader\Node\ExampleNode $example
     * @return boolean
     */
    public function supports(ExampleNode $example):bool
    {
        return $example->getSpecification()->getClassReflection()->hasMethod('setLaravel');
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
        $reflection = $example->getSpecification()->getClassReflection()->getMethod('setLaravel');
        $reflection->invokeArgs($context, [$this->laravel]);
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
     * Give this maintainer a high priority in the stack to ensure that Laravel
     * is bootstrapped early.
     *
     * @return int
     */
    public function getPriority():int
    {
        return 1000;
    }
}
