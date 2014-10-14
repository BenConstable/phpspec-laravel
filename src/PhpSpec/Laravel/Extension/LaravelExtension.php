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
        $getBoostrapPath = function ($manualPath = null)
        {
            // Configured absolute paths

            if (($manualPath !== null) && (strpos($manualPath, '/') === 0)) {
                return $manualPath . '/bootstrap';
            }

            // Paths relative to vendor/ dir

            if (!is_dir($vendorDir = getcwd() . '/vendor')) {
                $vendorDir = __DIR__ . '/../../../../../..';
            }

            if (($manualPath !== null) && is_dir($vendorDir . '/' . $manualPath)) {
                return $vendorDir . '/' . $manualPath . '/bootstrap';
            } else {
                return $vendorDir . '/../bootstrap';
            }
        };

        // Create & store Laravel wrapper

        $container->setShared(
            'laravel',
            function ($c) use ($getBoostrapPath)
            {
                $config = $c->getParam('laravel_extension');

                $bootstrapPath = $getBoostrapPath(
                    isset($config['framework_path']) ? $config['framework_path'] : null
                );

                if (file_exists($bootstrapPath . '/autoload.php')) {
                    require $bootstrapPath . '/autoload.php';
                } else {
                    die("Bootstrap dir not found at $bootstrapPath");
                }

                $laravel = new Laravel(
                    isset($config['testing_environment']) ? $config['testing_environment'] : null,
                    $bootstrapPath
                );

                return $laravel
                    ->setMigrateDatabase(isset($config['migrate_db']) ? $config['migrate_db'] : false)
                    ->setSeedDatabase(
                        isset($config['seed_db']) ? $config['seed_db'] : false,
                        isset($config['seed_class']) ? $config['seed_class'] : null
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
