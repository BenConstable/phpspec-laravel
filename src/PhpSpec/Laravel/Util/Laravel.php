<?php namespace PhpSpec\Laravel\Util;

use ErrorException;
use Illuminate\Foundation\Application;

/**
 * This class provides an entry point into Laravel for PhpSpec.
 */
class Laravel {

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    private $app;

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
     * Whether or not to migrate the database after booting Laravel.
     *
     * @var boolean
     */
    private $migrateDatabase;

    /**
     * Constructor.
     *
     * Setup application on construct.
     *
     * @param string  $env             Laravel testing environment. 'testing' by default
     * @param string  $bootstrapPath   Path to the Laravel bootstrap dir
     * @param boolean $migrateDatabase Whether or not to run db migrations after
     *                                 bootstrapping. False by default
     * @param mixed                    @see refreshApplication()
     * @return void
     */
    public function __construct($env, $bootstrapPath, $migrateDatabase = false, $app = null)
    {
        $this->env = $env ?: 'testing';
        $this->bootstrapPath = $bootstrapPath;
        $this->migrateDatabase = $migrateDatabase;

        $this->refreshApplication($app);
    }

    /**
     * Refresh the application instance.
     *
     * @param \Illuminate\Foundation\Application $app Optionally provide your own unbooted
     *                                                Laravel Application instance. This
     *                                                parameter can largely be ignored and
     *                                                is used just for unit testing
     * @return void
     */
    public function refreshApplication($app = null)
    {
        $this->app = $app instanceof Application ? $app : $this->createApplication();

        $this->app->setRequestForConsoleEnvironment();

        $this->app->boot();

        if ($this->migrateDatabase) {
            $this->app->make('artisan')->call('migrate');
        }
    }

    /**
     * Get the Laravel application environment being used.
     *
     * @return string Environment name
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Get the path to the Laravel bootstrap dir.
     *
     * @return string
     */
    public function getBootstrapPath()
    {
        return $this->bootstrapPath;
    }

    /**
     * Get the database migration flag.
     *
     * @return boolean
     */
    public function getMigrateDatabase()
    {
        return $this->migrateDatabase;
    }

    /**
     * Creates a Laravel application.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = $this->env;

        return require $this->bootstrapPath . '/start.php';
    }

    /**
     * Provide public access to the $app variable.
     *
     * Throw an exception if attempting to get anything else.
     *
     * @param  string          $name Name of variable to get
     * @return mixed                 Variable
     * @throws \ErrorException       If attempting to get something other than $app
     */
    public function __get($name)
    {
        if ($name === 'app') {
            return $this->app;
        }

        throw new ErrorException(
            "Attempting to get inaccessible or undefined property $name");
    }
}
