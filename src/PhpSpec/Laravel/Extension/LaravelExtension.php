<?php

namespace PhpSpec\Laravel\Extension;

use PhpSpec\Extension;
use InvalidArgumentException;
use PhpSpec\ServiceContainer;
use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\Laravel\Listener\LaravelListener;
use PhpSpec\Laravel\Runner\Maintainer\LaravelMaintainer;
use PhpSpec\Laravel\Runner\Maintainer\PresenterMaintainer;

/**
 * Setup the Laravel extension.
 *
 * Bootstraps Laravel and sets up some objects in the Container.
 */
class LaravelExtension implements Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('laravel', function () use ($params) {
            return new Laravel(
                isset($params['testing_environment']) ? $params['testing_environment'] : null,
                $this->getBootstrapPath(isset($params['framework_path']) ? $params['framework_path'] : null)
            );
        });

        $container->define('runner.maintainers.laravel', function (ServiceContainer $c) {
            return new LaravelMaintainer($c->get('laravel'));
        }, ['runner.maintainers']);

        $container->define('runner.maintainers.presenter', function (ServiceContainer $c) {
            return new PresenterMaintainer($c->get('formatter.presenter'));
        }, ['runner.maintainers']);

        $container->define('event_dispatcher.listeners.laravel', function (ServiceContainer $c) {
            return new LaravelListener($c->get('laravel'));
        }, ['event_dispatcher.listeners']);
    }

    /**
     * Get path to bootstrap file.
     *
     * @param null|string $path Optional bootstrap file path
     * @return null|string Bootstrap file path
     * @throws \InvalidArgumentException
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
     * @param string $path Path to check
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
