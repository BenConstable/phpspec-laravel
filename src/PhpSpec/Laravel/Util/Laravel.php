<?php

namespace PhpSpec\Laravel\Util;

use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

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
     * Path to the custom .env file.
     *
     * @var string
     */
    protected $envFile;

    /**
     * Constructor.
     *
     * @param string $env Laravel testing environment. 'testing' by default
     * @param string $appPath Path to the Laravel bootstrap dir
     * @param string $envPath Path to the custom .env file
     */
    public function __construct($env, $appPath, $envPath)
    {
        $this->env = $env ?: 'testing';
        $this->appPath = $appPath;
        $this->envFile = $envPath;
    }

    /**
     * Refresh the application instance.
     *
     * @param \Illuminate\Foundation\Application|null $app Optionally provide your own unbooted Laravel Application instance. This parameter can largely be ignored and is used just for unit testing
     * @return void
     */
    public function refreshApplication($app = null)
    {
        $this->app = $app instanceof Application ? $app : $this->createApplication();
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
     * Get the root Laravel application path.
     *
     * @return string Root laravel app path
     */
    public function getAppPath()
    {
        return $this->appPath;
    }

    public function getEnvFile()
    {
        return $this->envFile;
    }

    /**
     * Creates a Laravel application.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function createApplication()
    {
        $app = require $this->appPath;

        if ($this->getEnvFile()) {
            $app->loadEnvironmentFrom($this->getEnvFile());
        } else {
            putenv('APP_ENV=' . $this->getEnv());
        }

        $app->make(Kernel::class)->bootstrap();;

        Carbon::setTestNow(Carbon::now());

        return $app;
    }
}
