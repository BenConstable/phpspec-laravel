<?php namespace PhpSpec\Laravel;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Laravel\Util\Laravel;

/**
 * This behavior should be the base behavior for all of your regular PhpSpec
 * behaviors within your Larvel application.
 */
class LaravelObjectBehavior extends ObjectBehavior implements LaravelBehaviorInterface {

    /**
     * Laravel wrapper.
     *
     * @var \PhpSpec\Laravel\Util\Laravel
     */
    protected $laravel;

    /**
     * Bind Laravel wrapper to this behavior.
     *
     * @param \PhpSpec\Laravel\Util\Laravel $laravel Laravel wrapper
     * @return \PhpSpec\Laravel\LaravelObjectBehavior This
     */
    public function setLaravel(Laravel $laravel)
    {
        $this->laravel = $laravel;
        return $this;
    }

    /**
     * Force object initialization without serialization.
     *
     * @param Subject $subject
     */
    public function setSpecificationSubject(Subject $subject)
    {
        parent::setSpecificationSubject($subject);

        // Calling this forces the PhpSpec\Util\Instantiator class to be
        // avoided, which breaks when instantiating classes that have closures
        // as properties

        $this->getWrappedObject();
    }
}
