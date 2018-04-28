<?php

namespace PhpSpec\Laravel\Listener;

use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Event\SpecificationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This listener is used to setup the Laravel application for each spec.
 *
 * This only applies to specs that implement the LaravelBehaviorInterface.
 */
class LaravelListener implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'beforeSpecification' => ['beforeSpecification', 1]
        ];
    }

    /**
     * Run the 'beforeSpecification' hook.
     *
     * @param \PhpSpec\Event\SpecificationEvent $event
     * @return void
     */
    public function beforeSpecification(SpecificationEvent $event)
    {
        if ($event->getSpecification()->getClassReflection()->hasMethod('setLaravel')) {
            $this->laravel->refreshApplication();
        }
    }
}
