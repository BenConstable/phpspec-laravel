<?php namespace PhpSpec\Laravel\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\SpecificationInterface;

use PhpSpec\Laravel\Util\Laravel;

/**
 * This maintainer is used to bind the Laravel wrapper to nodes that implement
 * the `setLaravel` method.
 */
class LaravelMaintainer implements MaintainerInterface {

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
     * @return void
     */
    public function __construct(Laravel $laravel)
    {
        $this->laravel = $laravel;
    }

    /**
     * Check if this maintainer applies to the given node.
     *
     * Will check for the `setLaravel` method.
     *
     * @param \PhpSpec\Loader\Node\ExampleNode $example
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        return
            $example
                ->getSpecification()
                ->getClassReflection()
                ->hasMethod('setLaravel');
    }

    /**
     * Prepare the node using this maintainer.
     *
     * @param \PhpSpec\Loader\Node\ExampleNode    $example
     * @param \PhpSpec\SpecificationInterface     $context
     * @param \PhpSpec\Runner\MatcherManager      $matchers
     * @param \PhpSpec\Runner\CollaboratorManager $collaborators
     * @return void
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $reflection =
            $example
                ->getSpecification()
                ->getClassReflection()
                ->getMethod('setLaravel');

        $reflection->invokeArgs($context, array($this->laravel));
    }

    /**
     * Teardown the functionality on the node applied by this maintainer.
     *
     * @param \PhpSpec\Loader\Node\ExampleNode    $example
     * @param \PhpSpec\SpecificationInterface     $context
     * @param \PhpSpec\Runner\MatcherManager      $matchers
     * @param \PhpSpec\Runner\CollaboratorManager $collaborators
     * @return void
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }

    /**
     * Give this maintainer a high priority in the stack to ensure that Laravel
     * is bootstrapped early.
     *
     * @return int
     */
    public function getPriority()
    {
        return 1000;
    }
}
