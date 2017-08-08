<?php

namespace spec\PhpSpec\Laravel\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Foundation\Application;

class LaravelSpec extends ObjectBehavior
{
    private $appInst;

    function let(Application $appInst)
    {
        $this->appInst = $appInst;
    }

    function it_boots_in_the_testing_env_by_default()
    {
        $this->beConstructedWith(null, '.', null);
        $this->getEnv()->shouldBe('testing');
    }

    function it_allows_the_env_to_be_set_to_anything()
    {
        $this->beConstructedWith('whatever', '.', null);
        $this->getEnv()->shouldBe('whatever');
    }

    function it_allows_the_env_file_to_be_set()
    {
        $this->beConstructedWith(null, '.', '.phpspec.env');
        $this->getEnvFile()->shouldBe('.phpspec.env');
    }
}
