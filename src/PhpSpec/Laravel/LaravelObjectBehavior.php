<?php

namespace PhpSpec\Laravel;

use PhpSpec\ObjectBehavior;
use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Formatter\Presenter\Presenter;

/**
 * This behavior should be the base behavior for all of your regular Phpspec
 * behaviors within your Laravel application.
 */
class LaravelObjectBehavior extends ObjectBehavior implements LaravelBehaviorInterface
{
    /**
     * Laravel wrapper.
     *
     * @var \PhpSpec\Laravel\Util\Laravel
     */
    protected $laravel;

    /**
     * App presenter.
     *
     * @var \PhpSpec\Formatter\Presenter\Presenter
     */
    protected $presenter;

    /**
     * {@inheritdoc}
     */
    public function setLaravel(Laravel $laravel)
    {
        $this->laravel = $laravel;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->presenter = $presenter;

        return $this;
    }
}
