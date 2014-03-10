<?php namespace PhpSpec\Laravel;

use PhpSpec\ObjectBehavior;
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
}
