<?php
namespace PhpSpec\Laravel\Extension;

use InvalidArgumentException;
use PhpSpec\ServiceContainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Laravel\Listener\LaravelListener;
use PhpSpec\Laravel\Runner\Maintainer\LaravelMaintainer;
use PhpSpec\Laravel\Runner\Maintainer\PresenterMaintainer;
use PhpSpec\Laravel\Util\Laravel;

/**
 * Setup the Laravel extension.
 *
 * Bootstraps Laravel and sets up some objects in the Container.
 */
class LaravelExtension implements ExtensionInterface
{
    /**
     * Setup the Laravel extension.
     *
     * @param  \PhpSpec\ServiceContainer $container
     * @return void
     */
    public function load(ServiceContainer $container)
    {
        // Create & store Laravel wrapper

        $container->setShared(
            'laravel',
            function ($c) {
                $config = $c->getParam('laravel_extension');

                $laravel = new Laravel(
                    isset($config['testing_environment']) ? $config['testing_environment'] : null,
                    $this->getBootstrapPath(
                        isset($config['framework_path']) ? $config['framework_path'] : null
                    )
                );

                return $laravel;
            }
        );

        // Bootstrap maintainer to bind Laravel wrapper to specs

        $container->setShared(
            'runner.maintainers.laravel',
            function ($c) {
                return new LaravelMaintainer(
                    $c->get('laravel')
                );
            }
        );

        // Bootstrap maintainer to bind app Presenter to specs, so it
        // can be passed to custom matchers

        $container->setShared(
            'runner.maintainers.presenter',
            function ($c) {
                return new PresenterMaintainer(
                    $c->get('formatter.presenter')
                );
            }
        );

        // Bootstrap listener to setup Laravel application for specs

        $container->setShared(
            'event_dispatcher.listeners.laravel',
            function ($c) {
                return new LaravelListener($c->get('laravel'));
            }
        );
    }

    /**
     * Get path to bootstrap file.
     *
     * @param  null|string $path Optional bootstrap file path
     * @return null|string       Bootstrap file path
     */
    private function getBootstrapPath($path = null)
    {
        if (!$path) {
            $path = dirname($this->getVendorPath()) . '/bootstrap/app.php';
        } elseif (!$this->isAbsolutePath($path)) {
            $path = $this->getVendorPath() . '/' . $path;
        }

        if (!is_file($path)) {
            throw new InvalidArgumentException("App bootstrap at `{$path}` not found.");
        }

        return $path;
    }

    /**
     * Check if the given path is absolute.
     *
     * @param  $path   Path to check
     * @return boolean True if absolute, false if not
     */
    private function isAbsolutePath($path)
    {
        return ($path !== null) && (strpos($path, '/') === 0);
    }

    /**
     * Get path to vendor/ directory.
     *
     * @return string Absolute path to vendor directory
     */
    private function getVendorPath()
    {
        return realpath(__DIR__ . '/../../../../../..');
    }
}
