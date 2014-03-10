<?php namespace PhpSpec\Laravel;

use PhpSpec\SpecificationInterface;
use PhpSpec\Laravel\Util\Laravel;

/**
 * This behaviour provides more methods from
 *
 * \Illuminate\Foundation\Testing\TestCase
 *
 * that are specifically related to routing. The behaviour allows you to test
 * your routes in the context of your application, rather than unit testing
 * controller methods directly.
 */
class LaravelRouteBehaviour implements SpecificationInterface, LaravelBehaviorInterface {

    /**
     * Laravel wrapper.
     *
     * @var \PhpSpec\Laravel\Util\Laravel
     */
    protected $laravel;

    /**
     * Bind Laravel wrapper to this behavior.
     *
     * @param \PhpSpec\Laravel\Util\Laravel $laravel Laravel wrapper
     * @return \PhpSpec\Laravel\LaravelRouteBehaviour This
     */
    public function setLaravel(Laravel $laravel)
    {
        $this->laravel = $laravel;
        return $this;
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $parameters
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     * @param  bool    $changeHistory
     * @return \Illuminate\Http\Response
     */
    public function call()
    {
        call_user_func_array(array($this->laravel->client, 'request'), func_get_args());

        return $this->laravel->client->getResponse();
    }

    /**
     * Call the given HTTPS URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $parameters
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     * @param  bool    $changeHistory
     * @return \Illuminate\Http\Response
     */
    public function callSecure()
    {
        $parameters = func_get_args();

        $parameters[1] = 'https://localhost/'.ltrim($parameters[1], '/');

        return call_user_func_array(array($this, 'call'), $parameters);
    }

    /**
     * Call a controller action and return the Response.
     *
     * @param  string  $method
     * @param  string  $action
     * @param  array   $wildcards
     * @param  array   $parameters
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     * @param  bool    $changeHistory
     * @return \Illuminate\Http\Response
     */
    public function action($method, $action, $wildcards = array(), $parameters = array(), $files = array(), $server = array(), $content = null, $changeHistory = true)
    {
        $uri = $this->laravel->app['url']->action($action, $wildcards, true);

        return $this->call($method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Call a named route and return the Response.
     *
     * @param  string  $method
     * @param  string  $name
     * @param  array   $routeParameters
     * @param  array   $parameters
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     * @param  bool    $changeHistory
     * @return \Illuminate\Http\Response
     */
    public function route($method, $name, $routeParameters = array(), $parameters = array(), $files = array(), $server = array(), $content = null, $changeHistory = true)
    {
        $uri = $this->laravel->app['url']->route($name, $routeParameters);

        return $this->call($method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }
}
