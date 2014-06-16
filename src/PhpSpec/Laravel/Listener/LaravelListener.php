<?php namespace PhpSpec\Laravel\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpSpec\Event\SpecificationEvent;

use PhpSpec\Laravel\Util\Laravel;

/**
 * This listener is used to setup the Laravel application for each spec.
 *
 * This only applies to specs that implement the LaravelBehaviorInterface.
 */
class LaravelListener implements EventSubscriberInterface {

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
     * Get the events that this listener will listen to.
     *
     * @return array Events hash
     */
    public static function getSubscribedEvents()
    {
        return array(
            'beforeSpecification' => array('beforeSpecification', 1)
        );
    }

    /**
     * Run the `beforeSpecification` hook.
     *
     * @param \PhpSpec\Event\SpecificationEvent $event
     * @return void
     */
    public function beforeSpecification(SpecificationEvent $event)
    {
        $spec = $event->getSpecification();

        if ($spec->getClassReflection()->hasMethod('setLaravel')) {
            $this->laravel->refreshApplication();
        }
    }
}
