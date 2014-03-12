<?php namespace PhpSpec\Laravel\Util;

use Illuminate\Foundation\Testing\Client;
use Illuminate\Auth\UserInterface;

/**
 * This class implements most of the core functionality found in the:
 *
 * \Illuminate\Foundation\Testing\TestCase
 *
 * class. It provides an entry point into Laravel for PhpSpec.
 */
class Laravel {

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * The Laravel testing environment.
     *
     * @var string
     */
    private $env;

    /**
     * Path to the Laravel bootstrap dir.
     *
     * @var string
     */
    private $bootstrapPath;

    /**
     * Constructor.
     *
     * Setup application on construct.
     *
     * @param string $env           Laravel testing environment. 'testing' by default
     * @param string $bootstrapPath Path to the Laravel bootstrap dir
     * @return void
     */
    public function __construct($env, $bootstrapPath)
    {
        $this->env = $env ?: 'testing';
        $this->bootstrapPath = $bootstrapPath;

        $this->refreshApplication();
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    public function refreshApplication()
    {
        $this->app = $this->createApplication();

        $this->app->setRequestForConsoleEnvironment();

        $this->app->boot();
    }

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = $this->env;

        return require $this->bootstrapPath . '/start.php';
    }
}
