<?php namespace PhpSpec\Laravel\Runner\Maintainer;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\SpecificationInterface;

/**
 * This maintainer is used to bind the app Presenter to behaviours.
 */
class PresenterMaintainer implements MaintainerInterface {

    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;

    /**
     * Constructor.
     *
     * @param \PhpSpec\Formatter\Presenter\PresenterInterface $presenter
     * @return void
     */
    public function __construct(PresenterInterface $presenter)
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
    public function supports(ExampleNode $example)
    {
        return
            $example
                ->getSpecification()
                ->getClassReflection()
                ->hasMethod('setPresenter');
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
                ->getMethod('setPresenter');

        $reflection->invokeArgs($context, array($this->presenter));
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
     * Give this maintainer a high priority in the stack.
     *
     * @return int
     */
    public function getPriority()
    {
        return 1000;
    }
}
