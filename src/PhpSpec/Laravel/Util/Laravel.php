<?php namespace PhpSpec\Laravel\Util;

use App\Console\Kernel;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * This class provides an entry point into Laravel for PhpSpec.
 */
class Laravel
{

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The Laravel testing environment.
     *
     * @var string
     */
    protected $env;

    /**
     * Path to the root of the Laravel application.
     *
     * @var string
     */
    protected $appPath;

    /**
     * Whether or not to migrate the database after booting Laravel.
     *
     * @var boolean
     */
    protected $migrateDatabase = false;

    /**
     * Whether or not to seed the database after booting Laravel.
     *
     * @var boolean
     */
    protected $seedDatabase = false;

    /**
     * Database seeding class. 'DatabaseSeeder' by default.
     *
     * @var string
     */
    protected $seedClass = 'DatabaseSeeder';

    /**
     * @var string
     */
    protected $httpKernelClass = 'App\Http\Kernel';

    /**
     * @var string
     */
    protected $consoleKernelClass = 'PhpSpec\Laravel\Util\ConsoleKernel';

    /**
     * Constructor.
     * Setup application on construct.
     *
     * @param string $env              Laravel testing environment. 'testing' by
     *                                 default
     * @param string $appPath          Path to the Laravel bootstrap dir
     * @return void
     */
    public function __construct($env, $appPath)
    {
        $this->env     = $env ?: 'testing';
        $this->appPath = $appPath;
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
     * @return string Root laravel app path
     */
    public function getAppPath()
    {
        return $this->appPath;
    }

    /**
     * Get Http kernel class
     *
     * @return string
     */
    public function getHttpKernelClass()
    {
        return $this->httpKernelClass;
    }

    /**
     * Set the Http kernel class
     *
     * @param string $httpKernelClass
     * @return $this
     */
    public function setHttpKernelClass($httpKernelClass)
    {
        $this->httpKernelClass = $httpKernelClass;
        return $this;
    }

    /**
     * Get console Kernel class
     *
     * @return string
     */
    public function getConsoleKernelClass()
    {
        return $this->consoleKernelClass;
    }

    /**
     * Set console kernel class
     *
     * @param $consoleKernelClass
     * @return $this
     */
    public function setConsoleKernelClass($consoleKernelClass)
    {
        $this->consoleKernelClass = $consoleKernelClass;
        return $this;
    }

    /**
     * Get debug exception handler class
     *
     * @return string
     */
    public function getDebugExceptionHandlerClass()
    {
        return $this->debugExceptionHandlerClass;
    }


    /**
     * Set debug exception handler class
     *
     * @param $debugExceptionHandlerClass
     * @return $this
     */
    public function setDebugExceptionHandlerClass($debugExceptionHandlerClass)
    {
        $this->debugExceptionHandlerClass = $debugExceptionHandlerClass;
        return $this;
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
     * @param  boolean $migrateDatabase Database migration flag
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
     * @param  boolean $seedDatabase Database seeding flag
     * @param  string  $seedClass    Database seeding class
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
        date_default_timezone_set('UTC');

        Carbon::setTestNow(Carbon::now());

        $app = new Application(
            $this->getAppPath()
        );

        /**
         * Set the environment
         * Let the .env file override this
         */
        if (!getenv('APP_ENV')) {
            $_SERVER['argv'][] = '--env='.$this->getEnv();
        }

        $app->singleton(
            'Illuminate\Contracts\Http\Kernel',
            $this->getHttpKernelClass()
        );

        $app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            $this->getConsoleKernelClass()
        );

        $app->singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            $this->getDebugExceptionHandlerClass()
        );

        $app['router']->any(
            '/',
            function () {
            }
        );

        $app->make('Illuminate\Contracts\Http\Kernel')->handle(
            \Illuminate\Http\Request::capture()
        );

        /** @var Kernel $consoleKernel */
        $app->make('Illuminate\Contracts\Console\Kernel')->handle(
            new ArgvInput,
            new ConsoleOutput
        );

        return $app;
    }

    /**
     * Provide public access to the $app variable.
     * Throw an exception if attempting to get anything else.
     *
     * @param  string $name Name of variable to get
     * @return mixed                 Variable
     * @throws \ErrorException       If attempting to get something other than $app
     */
    public function __get($name)
    {
        if ($name === 'app') {
            return $this->app;
        }

        throw new ErrorException(
            "Attempting to get inaccessible or undefined property $name"
        );
    }
}
