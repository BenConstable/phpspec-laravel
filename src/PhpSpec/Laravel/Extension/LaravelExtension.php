<?php namespace PhpSpec\Laravel\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Laravel\Listener\LaravelListener;
use PhpSpec\Laravel\Runner\Maintainer\LaravelMaintainer;
use PhpSpec\Laravel\Runner\Maintainer\PresenterMaintainer;
use PhpSpec\Laravel\Util\Laravel;
use PhpSpec\ServiceContainer;
use Symfony\Component\Console\Application;

/**
 * Setup the Laravel extension.
 * Bootstraps Laravel and sets up some objects in the Container.
 */
class LaravelExtension implements ExtensionInterface
{

    /**
     * Setup the Laravel extension.
     *
     * @param \PhpSpec\ServiceContainer $container
     * @return void
     */
    public function load(ServiceContainer $container)
    {

        // Create & store Laravel wrapper

        $container->setShared(
            'laravel',
            function ($c) {
                $config = $c->getParam('laravel_extension');

                $environment = isset($config['testing_environment']) ? $config['testing_environment'] : null;

                $path = isset($config['framework_path']) ? $config['framework_path'] : null;

                $basePath = $this->getBasePath($path);

                $this->validateBasePath($basePath);

                $laravel = new Laravel($environment, $basePath);

                if (!empty($config['http_kernel_class'])) {
                    $laravel->setHttpKernelClass($config['http_kernel_class']);
                }

                if (!empty($config['console_kernel_class'])) {
                    $laravel->setConsoleKernelClass($config['console_kernel_class']);
                }

                if (!empty($config['debug_exception_handler_class'])) {
                    $laravel->setDebugExceptionHandlerClass($config['debug_exception_handler_class']);
                }

                return $laravel
                    ->setMigrateDatabase(isset($config['migrate_db']) ? $config['migrate_db'] : false)
                    ->setSeedDatabase(
                        isset($config['seed_db']) ? $config['seed_db'] : false,
                        isset($config['seed_class']) ? $config['seed_class'] : null
                    );
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
     * Get base path
     *
     * @param null $path
     * @return null|string
     */
    public function getBasePath($path = null)
    {
        // The application we are testing is already laravel
        if (!$path) {
            $path = dirname($this->getVendorPath());
            // If not an absolute path
        } elseif (!$this->isAbsolutePath($path)) {
            // make relative to vendor dir
            $path = $this->getVendorPath() . '/' . $path;
        }

        return $path;
    }

    /**
     * Validate base path
     *
     * @param $path
     */
    public function validateBasePath($path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException("Framework path `{$path}` not found.");
        }
    }

    /**
     * Is absolute path
     *
     * @param $path
     * @return bool
     */
    public function isAbsolutePath($path)
    {
        return ($path !== null) && (strpos($path, '/') === 0);
    }

    /**
     * Get vendor path
     *
     * @return string
     */
    public function getVendorPath()
    {
        return realpath(__DIR__ . '/../../../../../..');
    }

}
