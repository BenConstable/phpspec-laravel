<?php namespace PhpSpec\Laravel\Matcher\Eloquent;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Matcher\BasicMatcher;
use PhpSpec\Exception\Example\FailureException;

/**
 * This matcher allows you to verify the existence of an Eloquent relationship.
 *
 * Usage:
 *
 * $this->relation()->shouldDefineRelationship('belongsTo', 'OtherModel');
 *
 */
class DefineRelationshipMatcher extends BasicMatcher {

    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    protected $presenter;

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
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return Boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        return $name === 'defineRelationship';
    }

    /**
     * Check if the given subject is an Eloquent relationship of the expected
     * type.
     *
     * @param mixed $subject
     * @param array $arguments
     *
     * @return boolean
     */
    protected function matches($subject, array $arguments)
    {
        $relationClass = 'Illuminate\Database\Eloquent\Relations\\' . ucfirst($arguments[0]);

        if (null === $subject) {
            return false;
        } elseif (!($subject instanceof $relationClass)) {
            return false;
        } elseif (!($subject->getRelated() instanceof $arguments[1])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            'Expected %s relationship on %s but got %s',
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentString($arguments[1]),
            $this->presenter->presentValue($subject)
        ));
    }

    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            'Did not expect %s relationship on %s but got %s',
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentString($arguments[1]),
            $this->presenter->presentValue($subject)
        ));
    }
}
