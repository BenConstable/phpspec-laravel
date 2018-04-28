<?php

namespace PhpSpec\Laravel;

use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Formatter\Presenter\Presenter;

/**
 * Behaviours that implements this interface should provide a public method
 * with which to bind the Laravel wrapper instance.
 */
interface LaravelBehaviorInterface
{
    /**
     * Bind Laravel wrapper to the implementing object.
     *
     * @param \PhpSpec\Laravel\Util\Laravel $laravel Laravel wrapper
     * @return \PhpSpec\Laravel\LaravelBehaviorInterface
     */
    public function setLaravel(Laravel $laravel);

    /**
     * Bind the app Presenter to the implementing object.
     *
     * @param \PhpSpec\Formatter\Presenter\Presenter $presenter
     * @return \PhpSpec\Laravel\LaravelBehaviorInterface
     */
    public function setPresenter(Presenter $presenter);
}
