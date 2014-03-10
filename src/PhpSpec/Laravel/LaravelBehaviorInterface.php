<?php namespace PhpSpec\Laravel;

use PhpSpec\Laravel\Util\Laravel;

/**
 * Behaviours that implements this interface should provide a public method
 * with which to bind the Laravel wrapper instance.
 */
interface LaravelBehaviorInterface {

    /**
     * Bind Laravel wrapper to the implementing object.
     *
     * @param \PhpSpec\Laravel\Util\Laravel $laravel Laravel wrapper
     */
    public function setLaravel(Laravel $laravel);
}
