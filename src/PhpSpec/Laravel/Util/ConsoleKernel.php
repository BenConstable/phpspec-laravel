<?php namespace PhpSpec\Laravel\Util;

use Exception;
use Illuminate\Foundation\Console\Kernel;

/**
 * Class ConsoleKernel
 *
 * @package PhpSpec\Laravel\Util
 */
class ConsoleKernel extends Kernel {

    /**
     * Run the console application.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    public function handle($input, $output = null)
    {
        /**
         * We are probably using "phpspec run", don't throw an exception for that.
         */
        if ($input->getFirstArgument() === 'run') {
            return 1;
        }

        try
        {
            return parent::handle($input, $output);
        }
        catch (Exception $e)
        {
            $output->writeln((string) $e);

            return 1;
        }
    }

}
