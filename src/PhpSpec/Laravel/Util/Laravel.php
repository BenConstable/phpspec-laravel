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
     * The HttpKernel client instance.
     *
     * @var \Illuminate\Foundation\Testing\Client
     */
    public $client;

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

        $this->client = $this->createClient($this->app);

        $this->app->setRequestForConsoleEnvironment();

        $this->app->boot();
    }

    /**
     * Set the session to the given array.
     *
     * @param array $data
     * @return void
     */
    public function session(array $data)
    {
        $this->startSession();

        foreach ($data as $key => $value)
        {
            $this->app['session']->put($key, $value);
        }
    }

    /**
     * Flush all of the current session data.
     *
     * @return void
     */
    public function flushSession()
    {
        $this->startSession();

        $this->app['session']->flush();
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param \Illuminate\Auth\UserInterface $user
     * @param string $driver
     * @return void
     */
    public function be(UserInterface $user, $driver = null)
    {
        $this->app['auth']->driver($driver)->setUser($user);
    }

    /**
     * Seed a given database connection.
     *
     * @param string $class
     * @return void
     */
    public function seed($class = 'DatabaseSeeder')
    {
        $this->app['artisan']->call('db:seed', array('--class' => $class));
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

    /**
     * Create a new HttpKernel client instance.
     *
     * @param array $server
     * @return \Symfony\Component\HttpKernel\Client
     */
    protected function createClient($app, $servers = array())
    {
        return new Client($app, $servers);
    }

    /**
     * Start the session for the application.
     *
     * @return void
     */
    protected function startSession()
    {
        if (!$this->app['session']->isStarted())
        {
            $this->app['session']->start();
        }
    }
}
