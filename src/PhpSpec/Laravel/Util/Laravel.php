<?php namespace PhpSpec\Laravel\Util;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

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

        // We boot Eloquent separately again to prevent errors with the
        // PhpSpec Instantiator being unable to serilalize the Closures in
        // the application config

        $capsule          = new Capsule;
        $capsuleContainer = $capsule->getContainer();
        $eventDispatcher  = new Dispatcher($capsuleContainer);

        $capsuleContainer['config']['database.default'] = $this->app['config']->get('database.default');

        $capsule->setContainer($capsuleContainer);
        $capsule->setEventDispatcher($eventDispatcher);

        // Add connections from Laravel application

        foreach ($this->app['config']->get('database.connections') as $name => $c) {
            $capsule->addConnection($c, $name);
        }

        $capsule->bootEloquent();
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
