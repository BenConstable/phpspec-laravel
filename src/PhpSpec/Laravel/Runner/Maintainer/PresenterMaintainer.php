<?php
namespace PhpSpec\Laravel\Runner\Maintainer;

use PhpSpec\Formatter\Presenter\Presenter as PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\Maintainer\Maintainer as MaintainerInterface;
use PhpSpec\Specification as SpecificationInterface;

/**
 * This maintainer is used to bind the app Presenter to behaviours.
 */
class PresenterMaintainer implements MaintainerInterface
{
    /**
     * @var \PhpSpec\Formatter\Presenter\Presenter
     */
    private $presenter;

    /**
     * Constructor.
     *
     * @param  \PhpSpec\Formatter\Presenter\Presenter $presenter
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
     * @param  \PhpSpec\Loader\Node\ExampleNode $example
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
