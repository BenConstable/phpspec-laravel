<?php namespace PhpSpec\Laravel;

use PhpSpec\Formatter\Presenter\PresenterInterface;
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
     * App presenter.
     *
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    protected $presenter;

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
     * Bind the app Presenter to this behaviour.
     *
     * @param \PhpSpec\Formatter\Presenter\PresenterInterface $presenter
     * @return \PhpSpec\Laravel\LaravelObjectBehavior This
     */
    public function setPresenter(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }
}
