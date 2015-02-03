<?php

namespace spec\PhpSpec\Laravel\Util;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleKernelSpec extends ObjectBehavior
{
    function let(Application $application, Dispatcher $dispatcher)
    {
        $this->beConstructedWith($application, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\Laravel\Util\ConsoleKernel');
    }
    
    function it_throws_exception_when_a_command_does_not_exists(InputInterface $input, OutputInterface $output)
    {
        $input->getArguments()->willReturn(['nonexistent-command']);
        $this->shouldThrow('Exception')->during('handle', [$input, $output]);
    }

    function it_handles_the_phpspec_run_without_throwing_exception(InputInterface $input, OutputInterface $output)
    {
        $input->getFirstArgument()->willReturn('run');
        $this->handle($input, $output)->shouldBe(1);
    }
    
}
