<?php

namespace spec\PhpSpec\Laravel\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Foundation\Application;
use Illuminate\Console\Application as Console;

class LaravelSpec extends ObjectBehavior
{
    private $appInst;

    function let(Application $appInst)
    {
        $this->appInst = $appInst;
    }

    function it_boots_in_the_testing_env_by_default()
    {
        $this->beConstructedWith(null, '.', false);
        $this->getEnv()->shouldBe('testing');
    }

    function it_allows_the_env_to_be_set_to_anything()
    {
        $this->beConstructedWith('whatever', '.', false);
        $this->getEnv()->shouldBe('whatever');
    }

    function it_will_run_migrations_if_told_to(Console $console)
    {
        $console->call('migrate:install')->shouldBeCalled();
        $console->call('migrate:refresh')->shouldBeCalled();

        $this->appInst->setRequestForConsoleEnvironment()->shouldBeCalled();
        $this->appInst->boot()->shouldBeCalled();
        $this->appInst->make('artisan')->shouldBeCalled();
        $this->appInst->make('artisan')->willReturn($console);

        $this->beConstructedWith(null, '.', true);
        $this->getMigrateDatabase()->shouldBe(true);
        $this->refreshApplication($this->appInst);
    }

    function it_allows_access_to_the_app()
    {
        $this->appInst->setRequestForConsoleEnvironment()->shouldBeCalled();
        $this->appInst->boot()->shouldBeCalled();

        $this->beConstructedWith(null, '.', false);
        $this->refreshApplication($this->appInst);
        $this->app->shouldHaveType('Illuminate\Foundation\Application');
    }

    function it_throws_an_exception_when_trying_to_get_inaccessible_vars()
    {
        $this->beConstructedWith(null, '.', false);
        $this->shouldThrow('ErrorException')->during('__get', array('inaccessible'));
    }
}
