<?php namespace PhpSpec\Laravel\Util;

use ErrorException;
use Illuminate\Database\QueryException;
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
     * Whether or not to seed the database after booting Laravel.
     *
     * @var boolean
     */
    private $seedDatabase;

    /**
     * Database seeding class. 'DatabaseSeeder' by default.
     *
     * @var string
     */
    private $seedClass;

    /**
     * Constructor.
     *
     * Setup application on construct.
     *
     * @param string  $env             Laravel testing environment. 'testing' by
     *                                 default
     * @param string  $bootstrapPath   Path to the Laravel bootstrap dir
     * @return void
     */
    public function __construct($env, $bootstrapPath)
    {
        $this->env             = $env ?: 'testing';
        $this->bootstrapPath   = $bootstrapPath;
        $this->migrateDatabase = false;
        $this->seedDatabase    = false;
        $this->seedClass       = 'DatabaseSeeder';
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
            $artisan = $this->app->make('artisan');

            try {
                $artisan->call('migrate:install');
            } catch (QueryException $e) {
                // migration table is already installed
            }

            $artisan->call('migrate:refresh');

            if ($this->seedDatabase) {
                $artisan->call('db:seed', array('--class' => $this->seedClass));
            }
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
     * Set the database migration flag.
     *
     * @param  boolean                       $migrateDatabase Database migration flag
     * @return \PhpSpec\Laravel\Util\Laravel                  This, for chaining
     */
    public function setMigrateDatabase($migrateDatabase)
    {
        $this->migrateDatabase = $migrateDatabase;

        return $this;
    }

    /**
     * Get the database seeding flag.
     *
     * @return true
     */
    public function getSeedDatabase()
    {
        return $this->seedDatabase;
    }

    /**
     * Get the database seeding class name.
     *
     * @return string
     */
    public function getSeedDatabaseClass()
    {
        return $this->seedClass;
    }

    /**
     * Set the database seeding flag.
     *
     * @param  boolean                       $seedDatabase Database seeding flag
     * @param  string                        $seedClass    Database seeding class
     * @return \PhpSpec\Laravel\Util\Laravel               This, for chaining
     */
    public function setSeedDatabase($seedDatabase, $seedClass = null)
    {
        $this->seedDatabase = $seedDatabase;

        if ($seedClass !== null) {
            $this->seedClass = $seedClass;
        }

        return $this;
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
