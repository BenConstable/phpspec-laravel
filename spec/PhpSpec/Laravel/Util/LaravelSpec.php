<?php

namespace spec\PhpSpec\Laravel\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Foundation\Application;
use Illuminate\Console\Application as Console;

class LaravelSpec extends ObjectBehavior
{
    function let(Application $app)
    {
        $app->setRequestForConsoleEnvironment()->shouldBeCalled();
        $app->boot()->shouldBeCalled();
    }

    function it_boots_in_the_testing_env_by_default(Application $app)
    {
        $this->beConstructedWith(null, '.', false, $app);
        $this->getEnv()->shouldBe('testing');
    }

    function it_allows_the_env_to_be_set_to_anything(Application $app)
    {
        $this->beConstructedWith('whatever', '.', false, $app);
        $this->getEnv()->shouldBe('whatever');
    }

    function it_will_run_migrations_if_told_to(Application $app, Console $console)
    {
        $console->call('migrate:refresh')->shouldBeCalled();
        $app->make('artisan')->shouldBeCalled();
        $app->make('artisan')->willReturn($console);

        $this->beConstructedWith(null, '.', true, $app);
        $this->getMigrateDatabase()->shouldBe(true);
    }

    function it_allows_access_to_the_app(Application $app)
    {
        $this->beConstructedWith(null, '.', false, $app);
        $this->app->shouldHaveType('Illuminate\Foundation\Application');
    }

    function it_throws_an_exception_when_trying_to_get_inaccessible_vars(Application $app)
    {
        $this->beConstructedWith(null, '.', false, $app);
        $this->shouldThrow('ErrorException')->during('__get', array('inaccessible'));
    }
}
