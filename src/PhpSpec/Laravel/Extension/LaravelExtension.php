<?php namespace PhpSpec\Laravel\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

use PhpSpec\Laravel\Listener\LaravelListener;
use PhpSpec\Laravel\Runner\Maintainer\LaravelMaintainer;
use PhpSpec\Laravel\Runner\Maintainer\PresenterMaintainer;
use PhpSpec\Laravel\Util\Laravel;

/**
 * Setup the Laravel extension.
 *
 * Boostraps Laravel and sets up some objects in the Container.
 */
class LaravelExtension implements ExtensionInterface {

    /**
     * Setup the Laravel extension.
     *
     * @param \PhpSpec\ServiceContainer $container
     * @return void
     */
    public function load(ServiceContainer $container)
    {
        $bootstrapPath = __DIR__ . '/../../../../../../../bootstrap';

        // We do this so we can run the tests successfully

        if (file_exists($bootstrapPath . '/autoload.php')) {
            require $bootstrapPath . '/autoload.php';
        }

        // Create & store Laravel wrapper

        $container->setShared(
            'laravel',
            function ($c) use ($bootstrapPath)
            {
                $config = $c->getParam('laravel_extension');

                return new Laravel(
                    $config['testing_environment'],
                    $bootstrapPath,
                    isset($config['migrate_db']) ? $config['migrate_db'] : false
                );
            });

        // Bootstrap maintainer to bind Laravel wrapper to specs

        $container->setShared(
            'runner.maintainers.laravel',
            function ($c)
            {
                return new LaravelMaintainer(
                    $c->get('laravel')
                );
            }
        );

        // Bootstrap maintainer to bind app Presenter to specs, so it
        // can be passed to custom matchers

        $container->setShared(
            'runner.maintainers.presenter',
            function ($c)
            {
                return new PresenterMaintainer(
                    $c->get('formatter.presenter')
                );
            }
        );

        // Bootstrap listener to setup Laravel application for specs

        $container->setShared(
            'event_dispatcher.listeners.laravel',
            function ($c)
            {
                return new LaravelListener($c->get('laravel'));
            }
        );
    }
}
